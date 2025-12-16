<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CreatingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\TBUsers;
use App\Models\TBCountries;
use App\Models\TBSurvey;
use App\Models\TBSurveyResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SqueezyController extends Controller
{
    private $apiKey;
    private $signingSecret;
    private $storeId;
    private $productId;
    private $variantId;

    public function __construct()
    {
        $this->apiKey = env('LEMON_SQUEEZY_API_KEY');
        $this->signingSecret = env('LEMON_SQUEEZY_SIGNING_SECRET');
        $this->storeId = env('LEMON_SQUEEZY_STORE_ID');
        $this->productId = env('LEMON_SQUEEZY_PRODUCT_ID');
        $this->variantId = env('LEMON_SQUEEZY_VARIANT_ID');

  
    }

    /**
     * Create a checkout session for a user
     */
    public function createCheckout(Request $request)
    {
        $pid = @Session::get('pid') ?: null;
        
        if (!$pid) {
            return redirect('/')->with('error', 'Session expired. Please log in again.');
        }

        $user = TBUsers::where('pid', $pid)->first();
        
        if (!$user) {
            return redirect('/')->with('error', 'User not found. Please log in again.');
        }

        $checkoutUrl = $this->generateCheckoutUrl($user->pid, $user->email);

        if ($checkoutUrl) {
            return redirect($checkoutUrl);
        }

        return redirect()->back()->with('error', 'Unable to process donation at this time. Please try again.');
    }

    /**
     * Generate checkout URL
     */
    private function generateCheckoutUrl($pid, $userEmail)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ])->post('https://api.lemonsqueezy.com/v1/checkouts', [
                'data' => [
                    'type' => 'checkouts',
                    'attributes' => [
                        'checkout_data' => [
                            'email' => $userEmail,
                            'custom' => [
                                'pid' => (string) $pid
                            ]
                        ]
                    ],
                    'relationships' => [
                        'store' => [
                            'data' => [
                                'type' => 'stores',
                                'id' => $this->storeId
                            ]
                        ],
                        'variant' => [
                            'data' => [
                                'type' => 'variants',
                                'id' => $this->variantId
                            ]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['attributes']['url'] ?? null;
            }

            Log::error('Lemon Squeezy checkout creation failed', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Lemon Squeezy checkout error', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Handle Lemon Squeezy webhook
     */
    public function webhook(Request $request)
    {
        // Verify webhook signature
        if (!$this->verifySignature($request)) {
            Log::warning('Invalid Lemon Squeezy webhook signature', [
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        $eventName = $payload['meta']['event_name'] ?? null;

        Log::info('Lemon Squeezy webhook received', [
            'event' => $eventName,
            'order_id' => $payload['data']['id'] ?? null
        ]);

        try {
            switch ($eventName) {
                case 'order_created':
                    $this->handleOrderCreated($payload);
                    break;

                case 'subscription_created':
                case 'subscription_updated':
                case 'subscription_payment_success':
                    $this->handlePaymentSuccess($payload);
                    break;

                default:
                    Log::info('Unhandled webhook event', ['event' => $eventName]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Lemon Squeezy webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Verify webhook signature
     */
    private function verifySignature(Request $request)
    {
        $signature = $request->header('X-Signature');
        
        if (!$signature) {
            return false;
        }

        $payload = $request->getContent();
        $hash = hash_hmac('sha256', $payload, $this->signingSecret);

        return hash_equals($hash, $signature);
    }

    /**
     * Handle order created event
     */
    private function handleOrderCreated($payload)
    {
        $attributes = $payload['data']['attributes'];
        $customData = $attributes['custom_data'] ?? [];
        $pid = $customData['pid'] ?? null;

        if (!$pid) {
            Log::warning('No pid in order created webhook', [
                'order_id' => $payload['data']['id']
            ]);
            return;
        }

        $this->updateUserDonation($pid);

        Log::info('Order processed successfully', [
            'pid' => $pid,
            'order_id' => $payload['data']['id'],
            'total' => $attributes['total'] ?? 0
        ]);
    }

    /**
     * Handle payment success events
     */
    private function handlePaymentSuccess($payload)
    {
        $attributes = $payload['data']['attributes'];
        $customData = $attributes['custom_data'] ?? [];
        $pid = $customData['pid'] ?? null;

        if (!$pid) {
            Log::warning('No pid in payment success webhook', [
                'subscription_id' => $payload['data']['id']
            ]);
            return;
        }

        $this->updateUserDonation($pid);

        Log::info('Payment processed successfully', [
            'pid' => $pid,
            'event' => $payload['meta']['event_name']
        ]);
    }

    /**
     * Update user donated column to 1
     */
 private function updateUserDonation($pid)
{
    $user = TBUsers::where('pid', $pid)->first();

    if (!$user) {
        Log::error('User not found for donation update', ['pid' => $pid]);
        return;
    }

    // Mark the user as donated
    $user->donated = 1;
    $user->donated_at = Carbon::now();
    $user->save();

    // Get the referrer code safely
    $referralCode = $user->referalcode ?? null;

    if ($referralCode) {
        $referrer = TBUsers::where('code', $referralCode)->first();

        if ($referrer) {
            // Safely increment numeric columns
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
    } else {
        Log::info('User has no referral code, skipping referrer update', [
            'pid' => $pid
        ]);
    }

    Log::info('User donation status updated successfully', [
        'pid' => $pid,
        'donated' => 1
    ]);
}


    /**
     * Show donation page
     */
    public function showDonationPage()
    {
        $pid = @Session::get('pid') ?: null;
        
        if (!$pid) {
            return redirect()->route('login')->with('error', 'Please login to donate');
        }
        
        $user = TBUsers::where('pid', $pid)->first();
        
        return view('donation', [
            'user' => $user,
            'already_donated' => $user ? $user->donated == 1 : false
        ]);
    }

    /**
     * Redirect to Lemon Squeezy checkout
     */
    public function redirectToCheckout()
    {
        $pid = @Session::get('pid') ?: null;
        
        if (!$pid) {
            return redirect()->route('login')->with('error', 'Please login to donate');
        }
        
        $user = TBUsers::where('pid', $pid)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if ($user->donated == 1) {
            return redirect()->back()->with('info', 'You have already donated. Thank you!');
        }

        $checkoutUrl = $this->generateCheckoutUrl($user->pid, $user->email);

        if ($checkoutUrl) {
            return redirect($checkoutUrl);
        }

        return redirect()->back()->with('error', 'Unable to process donation at this time');
    }

    /**
     * Thank you page after successful donation
     */
    public function thankYou()
    {
        $pid = @Session::get('pid') ?: null;
        
        if (!$pid) {
            return redirect()->route('login');
        }
        
        $user = TBUsers::where('pid', $pid)->first();
        
        if ($user && $user->donated != 1) {
            // Update in case webhook hasn't fired yet
            $user->donated = 1;
            $user->donated_at = Carbon::now();
            $user->save();
        }

        return view('donation-thank-you', ['user' => $user]);
    }
}