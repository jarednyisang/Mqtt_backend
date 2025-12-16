<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\TBUsers;
use Carbon\Carbon;

class FastSpringController extends Controller
{
    private $storeId;
    private $apiUser;
    private $apiPassword;
    private $webhookSecret;

    public function __construct()
    {
        $this->storeId = env('FASTSPRING_STORE_ID');
        $this->apiUser = env('FASTSPRING_API_USER');
        $this->apiPassword = env('FASTSPRING_API_PASSWORD');
        $this->webhookSecret = env('FASTSPRING_WEBHOOK_SECRET');
    }

    /**
     * Redirect user to FastSpring checkout
     */
    public function redirectToCheckout()
    {
        $pid = @Session::get('pid') ?: null;

        if (!$pid) {
            return redirect('/')->with('error', 'Session expired. Please log in again.');
        }

        $user = TBUsers::where('pid', $pid)->first();

        if (!$user) {
            return redirect('/')->with('error', 'User not found. Please log in again.');
        }

        if ($user->donated == 1) {
            return redirect()->back()->with('info', 'You have already donated. Thank you!');
        }

        $checkoutUrl = $this->generateCheckoutUrl($user->pid, $user->email);

        if ($checkoutUrl) {
            return redirect($checkoutUrl);
        }

        return redirect()->back()->with('error', 'Unable to process donation at this time. Please try again.');
    }

    /**
     * Generate a FastSpring checkout URL
     */
    private function generateCheckoutUrl($pid, $email)
    {
        try {
            $response = Http::withBasicAuth($this->apiUser, $this->apiPassword)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://api.fastspring.com/sessions", [
                    "account" => [
                        "email" => $email,
                    ],
                    "items" => [
                        [
                            "product" => env('FASTSPRING_PRODUCT_PATH'), // e.g. "donation-product"
                            "quantity" => 1,
                        ]
                    ],
                    "tags" => [
                        "pid" => $pid
                    ],
                    "returnUrl" => "https://askworld.siliconhighland.com/thank-you",
                    "cancelUrl" => "https://askworld.siliconhighland.com/donation-canceled"
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['url'] ?? null;
            }

            Log::error('FastSpring checkout creation failed', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('FastSpring checkout error', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Handle FastSpring Webhook (payment, subscription, etc.)
     */
    public function webhook(Request $request)
    {
        // Verify HMAC Signature
        $signature = $request->header('X-FastSpring-Signature');
        if ($this->webhookSecret && $signature) {
            $computed = base64_encode(hash_hmac('sha256', $request->getContent(), $this->webhookSecret, true));
            if (!hash_equals($computed, $signature)) {
                Log::warning('Invalid FastSpring webhook signature');
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        $payload = $request->all();
        $eventType = $payload['type'] ?? null;

        Log::info('FastSpring Webhook Received', ['type' => $eventType]);

        try {
            switch ($eventType) {
                case 'order.completed':
                case 'subscription.charge.completed':
                    $this->handleOrderCompleted($payload);
                    break;

                default:
                    Log::info('Unhandled FastSpring event', ['event' => $eventType]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('FastSpring webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle order.completed or subscription payment
     */
    private function handleOrderCompleted($payload)
    {
        $data = $payload['data'] ?? [];
        $tags = $data['tags'] ?? [];
        $pid = $tags['pid'] ?? null;

        if (!$pid) {
            Log::warning('No PID found in FastSpring order');
            return;
        }

        $this->updateUserDonation($pid);

        Log::info('✅ Donation processed successfully', [
            'pid' => $pid,
            'order_id' => $data['id'] ?? null
        ]);
    }

    /**
     * Mark user as donated and update referral stats
     */
    private function updateUserDonation($pid)
    {
        $user = TBUsers::where('pid', $pid)->first();

        if (!$user) {
            Log::error('User not found for donation update', ['pid' => $pid]);
            return;
        }

        $user->donated = 1;
        $user->donated_at = Carbon::now();
        $user->save();

        $referralCode = $user->referalcode ?? null;

        if ($referralCode) {
            $referrer = TBUsers::where('code', $referralCode)->first();

            if ($referrer) {
                $referrer->totalreferal = ($referrer->totalreferal ?? 0) + 1;
                $referrer->pendingreferal = max(0, ($referrer->pendingreferal ?? 0) - 1);
                $referrer->availablebalance = ($referrer->availablebalance ?? 0) + 1;
                $referrer->save();

                Log::info('Referral stats updated successfully', [
                    'referrer_code' => $referralCode,
                    'referrer_pid' => $referrer->pid ?? null
                ]);
            } else {
                Log::warning('Referrer not found for donation update', [
                    'referral_code' => $referralCode
                ]);
            }
        }

        Log::info('User donation status updated successfully', [
            'pid' => $pid,
            'donated' => 1
        ]);
    }

    /**
     * Thank-you page
     */
    public function thankYou()
    {
        $pid = @Session::get('pid') ?: null;

        if (!$pid) {
            return redirect()->route('login');
        }

        $user = TBUsers::where('pid', $pid)->first();

        if ($user && $user->donated != 1) {
            $user->donated = 1;
            $user->donated_at = Carbon::now();
            $user->save();
        }

        return view('donation-thank-you', ['user' => $user]);
    }
}
