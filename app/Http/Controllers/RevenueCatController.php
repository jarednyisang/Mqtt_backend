<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use App\Models\TBUsers;


class RevenueCatController extends Controller
{
    /**
     * Update user Subscription status after successful RevenueCat purchase
     */
    public function updateBusinessSubscription(Request $request)
    {
        try {
            // Get request data
            $pid = $request->get('pid');
            $revenueCatUserId = $request->get('revenue_cat_user_id');
            $subscriptionProductId = $request->get('subscription_product_id');
            $transactionId = $request->get('transaction_id');
            $purchaseDate = $request->get('purchase_date');
            $amount = $request->get('amount');
            
            // Validate required fields
            if (!$pid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required field: pid'
                ], 400);
            }
            
            // Find the user by pid
            $user =TBUsers::where('pid', $pid)
                ->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found with pid: ' . $pid
                ], 404);
            }
            
            // Update user Subscription status
            $updateData = [
                'donated' => 1,
                'donated_at' => Carbon::now(),
            ];
            
            TBUsers::where('pid', $pid)
                ->update($updateData);
            
            // Log the Subscription
            $this->logDonation($pid, $amount, $transactionId, 'revenuecat_google_pay');
            
            // Get updated user info
            $updatedUser =TBUsers::where('pid', $pid)
                ->first();
            
            return response()->json([
                'success' => true,
                'message' => 'Subscription recorded successfully',
                'data' => [
                    'pid' => $pid,
                    'donated' => 1,
                    'donated_at' => $updatedUser->donated_at,
                    'fullname' => $updatedUser->fullname,
                    'email' => $updatedUser->email,
                    'amount' => $amount,
                    'transaction_id' => $transactionId
                ]
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('Failed to update Subscription status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Subscription status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle RevenueCat webhook for Subscription/subscription updates
     */
    public function handleRevenueCatWebhook(Request $request)
    {
        try {
            $event = $request->json()->all();
            $eventType = $event['event']['type'] ?? null;
            $productId = $event['event']['product_id'] ?? null;
            $userId = $event['event']['app_user_id'] ?? null; // This should be the pid
            $transactionId = $event['event']['transaction_id'] ?? null;
            $priceInPurchasedCurrency = $event['event']['price_in_purchased_currency'] ?? 0;
            
            Log::info('RevenueCat Webhook Received', [
                'event_type' => $eventType,
                'user_id' => $userId,
                'product_id' => $productId,
                'transaction_id' => $transactionId
            ]);
            
            // Extract pid from userId (assuming format like "user_123" or just "123")
            $pid = $this->extractPidFromUserId($userId);
            
            // Find user by pid
            $user =TBUsers::where('pid', $pid)
                ->first();
            
            if (!$user) {
                \Log::warning('User not found for RevenueCat user ID: ' . $userId . ' (pid: ' . $pid . ')');
                return response()->json(['received' => true], 200);
            }
            
            switch ($eventType) {
                case 'INITIAL_PURCHASE':
                case 'RENEWAL':
                case 'NON_RENEWING_PURCHASE':
                    $this->handleDonationSuccess($user, $event);
                    break;
                    
                case 'CANCELLATION':
                    $this->handleDonationCancellation($user, $event);
                    break;
                    
                case 'REFUND':
                    $this->handleDonationRefund($user, $event);
                    break;
            }
            
            return response()->json(['received' => true], 200);
            
        } catch (\Exception $e) {
            \Log::error('RevenueCat webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Extract pid from RevenueCat user ID
     */
    private function extractPidFromUserId($userId)
    {
        // If userId is like "user_123" or "pid_123", extract the number
        if (strpos($userId, '_') !== false) {
            $parts = explode('_', $userId);
            return end($parts);
        }
        
        // If it's already just a number, return it
        return $userId;
    }

    /**
     * Handle successful Subscription from webhook
     */
    private function handleDonationSuccess($user, $event)
    {
        $transactionId = $event['event']['transaction_id'] ?? null;
        $amount = $event['event']['price_in_purchased_currency'] ?? 0;
        $productId = $event['event']['product_id'] ?? null;
        
        $updateData = [
            'donated' => 1,
            'donated_at' => Carbon::now(),
        ];
        
      TBUsers::where('pid', $user->pid)
            ->update($updateData);
        
        $this->logDonation($user->pid, $amount, $transactionId, 'revenuecat_webhook_' . ($event['event']['type'] ?? 'unknown'));
        
        Log::info('Subscription recorded via webhook', [
            'pid' => $user->pid,
            'amount' => $amount,
            'transaction_id' => $transactionId
        ]);
    }

    /**
     * Handle Subscription cancellation from webhook
     */
    private function handleDonationCancellation($user, $event)
    {
        Log::info('Subscription cancelled via webhook', [
            'pid' => $user->pid,
            'event' => $event
        ]);
    }

    /**
     * Handle Subscription refund from webhook
     */
    private function handleDonationRefund($user, $event)
    {
        // Optional: You might want to revert the donated status
        TBUsers::where('pid', $user->pid)
            ->update([
                'donated' => 0,
                'donated_at' => null,
            ]);
        
        Log::info('Subscription refunded via webhook', [
            'pid' => $user->pid,
            'event' => $event
        ]);
    }

    /**
     * Log Subscription for audit trail
     */
    private function logDonation($pid, $amount, $transactionId, $source)
    {
        try {
            Log::info('Subscription Logged', [
                'pid' => $pid,
                'amount' => $amount,
                'transaction_id' => $transactionId,
                'source' => $source,
                'timestamp' => Carbon::now()
            ]);
            
            // Optional: Store in a separate Subscription table for better tracking
            /*
            \DB::table('Subscription_logs')->insert([
                'pid' => $pid,
                'amount' => $amount,
                'transaction_id' => $transactionId,
                'source' => $source,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            */
            
        } catch (\Exception $e) {
            Log::error('Failed to log Subscription: ' . $e->getMessage());
        }
    }

    /**
     * Get subscription/Subscription history for a user
     */
    public function getSubscriptionHistory(Request $request)
    {
        try {
            $pid = $request->get('pid');
            
            if (!$pid) {
                return response()->json([
                    'success' => false,
                    'message' => 'pid is required'
                ], 400);
            }
            
            $user =TBUsers::where('pid', $pid)
                ->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            $history = [
                'pid' => $user->pid,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'donated' => $user->donated,
                'donated_at' => $user->donated_at,
                'subscription_enddate' => $user->subscription_enddate,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $history
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get subscription history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get business/user by ID
     */
    public function getBusinessById(Request $request)
    {
        try {
            $pid = $request->get('pid');
            
            if (!$pid) {
                return response()->json([
                    'success' => false,
                    'message' => 'pid is required'
                ], 400);
            }
            
            $user =TBUsers::where('pid', $pid)
                ->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'pid' => $user->pid,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'donated' => $user->donated,
                    'donated_at' => $user->donated_at,
                    'usertype' => $user->usertype,
                    'currency_symbol' => $user->currency_symbol,
                    'lifetimebalance' => $user->lifetimebalance,
                    'availablebalance' => $user->availablebalance,
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manually activate Subscription status (for testing or admin purposes)
     */
    public function activateSubscriptionManually(Request $request)
    {
        try {
            $pid = $request->get('pid');
            $amount = $request->get('amount', 0);
            
            if (!$pid) {
                return response()->json([
                    'success' => false,
                    'message' => 'pid is required'
                ], 400);
            }
            
            $user =TBUsers::where('pid', $pid)
                ->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            $updateData = [
                'donated' => 1,
                'donated_at' => Carbon::now(),
            ];
            TBUsers::where('pid', $pid)
                ->update($updateData);
            
            $this->logDonation($pid, $amount, 'MANUAL_ACTIVATION', 'manual');
            
            return response()->json([
                'success' => true,
                'message' => 'Subscription status activated successfully',
                'donated_at' => Carbon::now()->toISOString()
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate Subscription status: ' . $e->getMessage()
            ], 500);
        }
    }
}