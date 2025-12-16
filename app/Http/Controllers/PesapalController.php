<?php

namespace App\Http\Controllers;

use App\Models\TBC2BpesapalPayments;
use App\Models\TBUsers;
use App\Models\PriceList;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PesapalController extends Controller
{
    // Pesapal Credentials
    const CONSUMER_KEY = "l9tvGSDsYrAdyasdghQI0AYNrvwUjL6i";
    const CONSUMER_SECRET = "sHZ1G8k3BU//L1l3adukjXP35cg=";
    const API_URL = "https://pay.pesapal.com/v3"; // Production URL
    // const API_URL = "https://cybqa.pesapal.com/pesapalv3"; // Sandbox URL (uncomment for testing)

    private $accessToken = null;
    private $tokenExpiry = null;

    /** -------------------------
     *  HELPER FUNCTIONS
     *  ------------------------- */

    /**
     * Get or refresh Pesapal access token
     */
    private function getAccessToken()
    {
        // Return cached token if still valid
        if ($this->accessToken && $this->tokenExpiry && now()->lt($this->tokenExpiry)) {
            return $this->accessToken;
        }

        $url = self::API_URL . "/api/Auth/RequestToken";

        $payload = [
            'consumer_key' => self::CONSUMER_KEY,
            'consumer_secret' => self::CONSUMER_SECRET
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Content-Type: application/json"
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            Log::error("Pesapal Access Token cURL Error: " . $error);
            return null;
        }
        
        curl_close($curl);

        if ($httpCode !== 200) {
            Log::error("Pesapal Access Token HTTP Error: " . $httpCode . " Response: " . $response);
            return null;
        }

        $responseData = json_decode($response, true);
        
        if (!isset($responseData['token'])) {
            Log::error("No access token in Pesapal response", $responseData);
            return null;
        }

        // Cache token (Pesapal tokens typically expire after 5 minutes)
        $this->accessToken = $responseData['token'];
        $this->tokenExpiry = now()->addMinutes(4); // Refresh 1 minute before expiry

        return $this->accessToken;
    }

    /**
     * Register IPN (Instant Payment Notification) URL
     */
    private function registerIPN()
    {
        $access_token = $this->getAccessToken();
        
        if (!$access_token) {
            return null;
        }

        $url = self::API_URL . "/api/URLSetup/RegisterIPN";

        $payload = [
            'url' => 'https://survey.siliconhighland.com/SurveyHub/api/pesapal/ipn',
            'ipn_notification_type' => 'GET'
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer " . $access_token
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($curl);
        curl_close($curl);

        $responseData = json_decode($response, true);
        
        if (isset($responseData['ipn_id'])) {
            return $responseData['ipn_id'];
        }

        Log::error("Failed to register IPN", $responseData);
        return null;
    }

    /**
     * Format and validate Kenyan phone number
     */
    private function formatPhoneNumber($phone)
    {
        // Remove any spaces, dashes, or other characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove leading zeros
        $phone = ltrim($phone, '0');
        
        // Remove country code if already present
        if (substr($phone, 0, 3) === '254') {
            $phone = substr($phone, 3);
        }
        
        // Check if it's a valid Kenyan mobile number
        if (!preg_match('/^[701]\d{8}$/', $phone)) {
            return false;
        }
        
        // Return with country code
        return '+254' . $phone;
    }

    /** -------------------------
     *  SUBMIT ORDER REQUEST
     *  ------------------------- */

    public function submitOrderRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'User' => 'required|string',
            'Amount' => 'required|numeric|min:1',
            'Phone' => 'required|string',
            'Pid' => 'required',
            'Account_reference' => 'required|string',
            'Transaction_desc' => 'required|string',
            'Email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $systemuser = $request->get('User');
        $pid = $request->get('Pid');
        $accountReference = $request->get('Account_reference');
        $transactionDesc = $request->get('Transaction_desc');
        $amount = $request->get('Amount');
        $phone = $request->get('Phone');
        $email = $request->get('Email', 'customer@example.com');

        // Format and validate phone number
        $phoneNumber = $this->formatPhoneNumber($phone);
        
        if (!$phoneNumber) {
            return response()->json([
                'error' => 'Invalid phone number format. Please use a valid Kenyan mobile number.',
                'example' => '0712345678 or 712345678'
            ], 400);
        }

        // Get access token
        $access_token = $this->getAccessToken();
        
        if (!$access_token) {
            return response()->json([
                'error' => 'Failed to get access token from Pesapal API'
            ], 500);
        }

        // Register IPN if not already registered
        $ipn_id = $this->registerIPN();
        
        if (!$ipn_id) {
            Log::warning("IPN registration failed, continuing without IPN");
        }

        // Generate unique merchant reference
        $merchantReference = 'ORDER-' . time() . '-' . Str::random(6);

        $payload = [
            'id' => $merchantReference,
            'currency' => 'KES',
            'amount' => (float)$amount,
            'description' => $transactionDesc,
            'callback_url' => 'https://survey.siliconhighland.com/SurveyHub/api/pesapal/callback',
            'notification_id' => $ipn_id,
            'billing_address' => [
                'email_address' => $email,
                'phone_number' => $phoneNumber,
                'country_code' => 'KE',
                'first_name' => $systemuser,
                'middle_name' => '',
                'last_name' => '',
                'line_1' => '',
                'line_2' => '',
                'city' => '',
                'state' => '',
                'postal_code' => '',
                'zip_code' => ''
            ]
        ];

        $url = self::API_URL . '/api/Transactions/SubmitOrderRequest';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            Log::error("Pesapal Submit Order cURL Error: " . $error);
            
            return response()->json([
                'error' => 'Network error occurred',
                'details' => $error
            ], 500);
        }
        
        curl_close($curl);

        $responseData = json_decode($response, true);

        if (isset($responseData['order_tracking_id']) && isset($responseData['redirect_url'])) {
            
            // Store initial transaction record
            $dateTime = Carbon::now();
            $lastRec2 = TBC2BpesapalPayments::orderBy('id', "desc")->first();
            $id = $lastRec2 ? $lastRec2->id + 1 : 1;
            $joinAfter = $lastRec2 ? $lastRec2->id + 11 : 11;
            
            // Calculate values (will be updated on successful payment)
            $totalContribution = $amount * 10;
            $payout = $amount * 9;
            $systemfee = $amount;
            
            TBC2BpesapalPayments::create([
                'id' => $id,
                'date' => $dateTime,
                 'pid' => $pid,
                'merchant_request_id' => $merchantReference,
                'checkout_request_id' => $responseData['order_tracking_id'],
                'result_code' => null,
                'result_desc' => 'Pending',
                'identifier' => 1,
                'transaction_desc' => $transactionDesc,
                'amount' => $amount,
                'total_contribution' => $totalContribution,
                'payout' => $payout,
                'systemfee' => $systemfee,
                'mpesa_receipt_number' => null,
                'transaction_date' => null,
                'phone_number' => $phoneNumber,
                'raw_response' => json_encode($responseData),
                'join_after' => $joinAfter,
                'processed' => 0,
                'processed_at' => null,
                'b2c_mpesa_receipt' => null,
            ]);

            return response()->json([
                'success' => true,
                'order_tracking_id' => $responseData['order_tracking_id'],
                'merchant_reference' => $responseData['merchant_reference'],
                'redirect_url' => $responseData['redirect_url'],
                'message' => 'Payment request created successfully. Redirect user to payment page.'
            ]);
        } else {
            Log::error("Pesapal Submit Order Failed", $responseData);
            
            return response()->json([
                'error' => 'Failed to initiate payment',
                'details' => $responseData,
                'phone_used' => $phoneNumber
            ], 400);
        }
    }

    /** -------------------------
     *  CALLBACK & IPN HANDLERS
     *  ------------------------- */

    /**
     * Handle callback when user completes/cancels payment
     */
    public function handleCallback(Request $request)
    {
        $orderTrackingId = $request->query('OrderTrackingId');
        $merchantReference = $request->query('OrderMerchantReference');

        Log::info("Pesapal Callback received", [
            'order_tracking_id' => $orderTrackingId,
            'merchant_reference' => $merchantReference,
            'all_params' => $request->all()
        ]);

        if (!$orderTrackingId) {
            return response()->json(['error' => 'Missing order tracking ID'], 400);
        }

        // Query transaction status
        $status = $this->getTransactionStatus($orderTrackingId);

        if ($status) {
            // Process the payment if completed
            if ($status['payment_status_description'] === 'Completed') {
                $this->processSuccessfulPayment($status);
            }
            
            // Redirect user to success/failure page based on status
            $redirectUrl = $status['payment_status_description'] === 'Completed' 
                ? 'https://survey.siliconhighland.com/success?order_id=' . $orderTrackingId
                : 'https://survey.siliconhighland.com/failed?order_id=' . $orderTrackingId;
            
            return redirect($redirectUrl);
        }

        return response()->json(['message' => 'Transaction status checked']);
    }

    /**
     * Handle IPN (Instant Payment Notification)
     */
    public function handleIPN(Request $request)
    {
        $orderTrackingId = $request->query('OrderTrackingId');
        $merchantReference = $request->query('OrderMerchantReference');

        Log::info("Pesapal IPN received", [
            'order_tracking_id' => $orderTrackingId,
            'merchant_reference' => $merchantReference,
            'all_params' => $request->all()
        ]);

        if (!$orderTrackingId) {
            return response()->json(['error' => 'Missing order tracking ID'], 400);
        }

        // Query transaction status and update database
        $status = $this->getTransactionStatus($orderTrackingId);

        if ($status && $status['payment_status_description'] === 'Completed') {
            $this->processSuccessfulPayment($status);
        }

        return response()->json(['message' => 'IPN processed']);
    }

    /**
     * Get transaction status from Pesapal
     */
    private function getTransactionStatus($orderTrackingId)
    {
        $access_token = $this->getAccessToken();
        
        if (!$access_token) {
            return null;
        }

        $url = self::API_URL . "/api/Transactions/GetTransactionStatus?orderTrackingId=" . $orderTrackingId;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer " . $access_token
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);

        if ($httpCode !== 200) {
            Log::error("Failed to get transaction status", ['response' => $response]);
            return null;
        }

        $responseData = json_decode($response, true);
        
        Log::info("Transaction Status", $responseData);

        return $responseData;
    }

    /**
     * Process successful payment and update database
     */
    private function processSuccessfulPayment($statusData)
    {
        $orderTrackingId = $statusData['order_tracking_id'];
        
        // Find existing record
        $transaction = TBC2BpesapalPayments::where('checkout_request_id', $orderTrackingId)->first();

        if (!$transaction) {
            Log::error("Transaction not found for order_tracking_id: " . $orderTrackingId);
            return;
        }

        // Check if already processed
        if ($transaction->processed == 1) {
            Log::info("Transaction already processed: " . $orderTrackingId);
            return;
        }

        $dateTime = Carbon::now();
        $amount = $statusData['amount'];
        $totalContribution = $amount * 10;
        $payout = $amount * 9;
        $systemfee = $amount;

        $lastRec2 = TBC2BpesapalPayments::orderBy('id', "desc")->first();
        $joinAfter = $lastRec2 ? $lastRec2->id + 11 : 11;

        $updateData = [
            'result_code' => $statusData['status_code'],
            'result_desc' => $statusData['payment_status_description'],
            'total_contribution' => $totalContribution,
            'payout' => $payout,
            'systemfee' => $systemfee,
            'mpesa_receipt_number' => $statusData['payment_account'] ?? null,
            'transaction_date' => $dateTime,
            'raw_response' => json_encode($statusData),
            'join_after' => $joinAfter,
            'processed' => 1,
            'processed_at' => $dateTime,
        ];

        $transaction->update($updateData);

         if (!empty($transaction->pid)) {
        try {
            TBUsers::where('pid', $transaction->pid)
                ->update([
                    'donated' => 1,
                    'donated_at' => Carbon::now(),
                ]);
            
            Log::info("User donation status updated", [
                'pid' => $transaction->pid,
                'order_tracking_id' => $orderTrackingId
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update user donation status", [
                'pid' => $transaction->pid,
                'error' => $e->getMessage()
            ]);
        }
    } else {
        Log::warning("No pid found in transaction record", [
            'order_tracking_id' => $orderTrackingId
        ]);
    }

        Log::info("Payment processed successfully", ['order_tracking_id' => $orderTrackingId]);
    }

    /**
     * Manually check transaction status (for debugging)
     */
    public function checkTransactionStatus(Request $request)
    {
        $orderTrackingId = $request->query('order_tracking_id');

        if (!$orderTrackingId) {
            return response()->json(['error' => 'order_tracking_id is required'], 400);
        }

        $status = $this->getTransactionStatus($orderTrackingId);

        if ($status) {
            return response()->json($status);
        }

        return response()->json(['error' => 'Failed to get transaction status'], 500);
    }
    /**
     * List all transactions with optional filters
     */
    public function listTransactions(Request $request)
    {
        try {
            $query = TBC2BpesapalPayments::query();

            // Filter by processed status
            if ($request->has('processed')) {
                $query->where('processed', $request->get('processed'));
            }

            // Filter by phone number
            if ($request->has('phone')) {
                $query->where('phone_number', 'LIKE', '%' . $request->get('phone') . '%');
            }

            // Filter by date range
            if ($request->has('from_date')) {
                $query->where('date', '>=', $request->get('from_date'));
            }

            if ($request->has('to_date')) {
                $query->where('date', '<=', $request->get('to_date'));
            }

            // Order by latest
            $transactions = $query->orderBy('id', 'desc')->paginate(50);

            return response()->json($transactions);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}