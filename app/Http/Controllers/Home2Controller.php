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



use Carbon\Carbon;

class Home2Controller
{

    protected $creatingService;
    public function __construct(CreatingService $creatingService)
    {
        $this->creatingService = $creatingService;
    }


public function store_withdrawals(Request $request)
{
    try {
        $pid = $request->get('pid');

        if (!$pid) {
            return response()->json([
                'error' => true,
                'message' => 'Session expired. Please log in again.',
            ], 400);
        }

        $getuserdetails = TBUsers::where('pid', $pid)->first();

        if (!$getuserdetails) {
            return response()->json([
                'error' => true,
                'message' => 'User not found.',
            ], 400);
        }

        $donated = $getuserdetails->donated;

        // ✅ Validate request
        try {
            $this->creatingService->validateWithdrawal($request);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Missing data: ' . $e->getMessage(),
            ], 400);
        }

        try {
            $Amount = $request->get('amount');
            $availablebalance = $getuserdetails->availablebalance;

            if ($Amount > $availablebalance) {
                return response()->json([
                    'error' => true,
                    'message' => 'You do not have enough balance to withdraw.',
                ], 400);
            }

            if ($donated != 1) {
                return response()->json([
                    'error' => true,
                    'message' => 'Your support covers registration and verification, transaction costs, system upkeep, and helps us bring more companies on board to offer more surveys.',
                ], 400);
            }

            // ✅ Subtract balance
            $getuserdetails->availablebalance -= $Amount;
            $getuserdetails->save();

            // ✅ Create withdrawal record
            $this->creatingService->createWithdrawal($request);

            return response()->json([
                'error' => false,
                'message' => 'Withdrawal submitted successfully.',
                'data' => [
                    'new_balance' => $getuserdetails->availablebalance,
                    'amount_withdrawn' => $Amount,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create withdrawal: ',
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'Unexpected error ',
        ], 500);
    }
}


public function store_response(Request $request)
{
    try {
        $pid = $request->get('pid');

        if (!$pid) {
            return response()->json([
                'error' => true,
                'message' => 'Session expired. Please log in again.',
            ], 401);
        }

        $getuserdetails = TBUsers::where('pid', $pid)->first();

        if (!$getuserdetails) {
            return response()->json([
                'error' => true,
                'message' => 'User not found.',
            ], 400);
        }

        $donated = $getuserdetails->donated;
          if ($donated != 1) {
                return response()->json([
                    'error' => true,
                    'message' => 'Your support covers registration and verification, transaction costs, system upkeep, and helps us bring more companies on board to offer more surveys.',
                ], 400);
            }

        // ✅ Validate request data
        try {
            $this->creatingService->validateResponse($request);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Missing data: ' . $e->getMessage(),
            ], 400);
        }

        try {
            $surveyAmount = $request->get('surveyAmount');
            $surveyId = $request->get('surveyId');

            $user = TBUsers::where('pid', $pid)->first();

            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'User not found.',
                ], 400);
            }

            // ✅ Check if user already responded
            $existingResponse = TBSurveyResponse::where([
                'pid' => $pid,
                'survey_id' => $surveyId,
            ])->first();

            if ($existingResponse) {
                return response()->json([
                    'error' => true,
                    'message' => 'You have already taken this survey. Try another one.',
                ], 400);
            }

            // ✅ Update user balance and survey count
            $user->availablebalance += $surveyAmount;
            $user->survey_completed += 1;
            $user->save();

            // ✅ Create response entry
            $this->creatingService->createResponse($request);

            return response()->json([
                'error' => false,
                'message' => 'Survey submitted successfully.',
                'data' => [
                    'new_balance' => $user->availablebalance,
                    'survey_completed' => $user->survey_completed,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create survey response: ' . $e->getMessage(),
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'Unexpected error: ' . $e->getMessage(),
        ], 500);
    }
}



  
   

  




    
}