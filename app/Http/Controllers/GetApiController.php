<?php

namespace App\Http\Controllers;
use App\Models\TBUsers;
use App\Models\TBCountries;
use App\Models\TBSurvey;
use App\Models\TBSurveyResponse;
use App\Models\TBTransactions;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;



use Carbon\Carbon;

class GetApiController
{

public function dashboard($pid)
{
    try {
        if ($pid == null) {
            return response()->json([
                'error' => true,
                'message' => 'Session expired. Please log in again.'
            ], 400);
        }

        $getuserdetails = TBUsers::where('pid', $pid)->first();

        if (!$getuserdetails) {
            return response()->json([
                'error' => true,
                'message' => 'User not found.'
            ], 400);
        }

        // 🧍‍♂️ User details
        $donated = $getuserdetails->{'donated'};
        $lifetimetotal = $getuserdetails->{'lifetimebalance'};
        $totalreferal = $getuserdetails->{'totalreferal'};
        $pendingreferal = $getuserdetails->{'pendingreferal'};
        $availablebalance = $getuserdetails->{'availablebalance'};
        $completedsurvey = $getuserdetails->{'survey_completed'};

        // 🧾 Recent surveys (latest 4)
        $getsurveys = TBSurvey::orderBy('id', 'desc')
            ->take(4)
            ->get();

        $surveylist = [];

        foreach ($getsurveys as $survey) {
            $surveylist[] = [
                "id" => $survey->{'id'},
                "title" => $survey->{'surveytitle'},
                "reward" => $survey->{'rewardamount'},
                "type" => $survey->{'surveytype'},
                "description" => $survey->{'surveydescription'},
                "created_at" => $survey->{'date'},
            ];
        }

        // 📅 Define date ranges
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        // 🧮 Count survey responses
        $thisWeekCount = TBSurveyResponse::where('pid', $pid)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->count();

        $thisMonthCount = TBSurveyResponse::where('pid', $pid)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->count();

        $thisYearCount = TBSurveyResponse::where('pid', $pid)
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->count();

        // ✅ Prepare API response
        $dashboardData = [
            "lifetime_total" => $lifetimetotal,
            "total_referral" => $totalreferal,
            "pending_referral" => $pendingreferal,
            "available_balance" => $availablebalance,
            "completed_survey" => $completedsurvey,
            "donated" => $donated,
            "surveys" => $surveylist,
            "stats" => [
                "this_week" => $thisWeekCount,
                "this_month" => $thisMonthCount,
                "this_year" => $thisYearCount
            ]
        ];

        return response()->json([
            'data' => $dashboardData
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'Failed to fetch dashboard data: ' . $e->getMessage()
        ], 400);
    }
}

public function availablesurvey($pid)
{
    try {

        if ($pid == null) {
            return response()->json([
                'error' => true,
                'message' => 'Session expired. Please log in again.'
            ], 400);
        }

        $getuserdetails = TBUsers::where('pid', $pid)->first();

        $getsurveys = TBSurvey::orderBy('id', 'desc')->take(8)->get();

        $surveylist = [];

        foreach ($getsurveys as $survey) {
            $surveylist[] = [
                "id" => $survey->{'id'},
                "title" => $survey->{'surveytitle'},
                "reward" => $survey->{'rewardamount'},
                "type" => $survey->{'surveytype'},
                "description" => $survey->{'surveydescription'},
                "created_at" => $survey->{'date'},
            ];
        }

        return response()->json([
            'data' => $surveylist
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 400);
    }
}

public function referrals($pid)
{
    try {

        if ($pid == null) {
            return response()->json([
                'error' => true,
                'message' => 'Session expired. Please log in again.'
            ], 400);
        }

        $getuserdetails = TBUsers::where('pid', $pid)->first();
        $code = $getuserdetails->{'code'};

        // Fetch referrals with country relationship
        $getrefferals = TBUsers::with('country')
            ->where('referalcode', $code)
            ->orderBy('id', 'desc')
            ->get();

        $referralsList = [];

        foreach ($getrefferals as $ref) {
            $referralsList[] = [
                "fullname" => $ref->{'fullname'},
                "country" => $ref->country ? $ref->country->{'country_name'} : null,
            ];
        }

        return response()->json([
            'data' => $referralsList
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 400);
    }
}



public function transactions($pid)
{
    try {
        if ($pid == null) {
            return response()->json([
                'error' => true,
                'message' => 'Session expired. Please log in again.'
            ], 400);
        }

        $transactions = TBTransactions::where('pid', $pid)
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        $transactionsList = [];

        foreach ($transactions as $trx) {
            $transactionsList[] = [
                "id" => $trx->{'id'},
                "date" => $trx->{'date'},
                "transaction_type" => $trx->{'transaction_type'},
                "method" => $trx->{'method'},
                "account_details" => $trx->{'account_details'},
                "amount" => $trx->{'amount'},
                "status" => $trx->{'status'},
            ];
        }

        return response()->json([
            'data' => $transactionsList
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 400);
    }
}






public function completedSurvey($pid)
{
    try {
        if ($pid == null) {
            return response()->json([
                'error' => true,
                'message' => 'Session expired. Please log in again.'
            ], 400);
        }

        $getsurveys = TBSurveyResponse::with('survey')
            ->where('pid', $pid)
            ->orderBy('id', 'desc')
            ->get();

        $surveyList = [];

        foreach ($getsurveys as $survey) {
            
            $surveyList[] = [
                "id" => $survey->{'id'},
                "date" => $survey->{'date'},
                "survey_id" => $survey->{'survey_id'},
                "survey_amount" => $survey->{'survey_amount'},
                "response" => $survey->{'response'},
                "status" => $survey->{'status'},
                "survey_title" => $survey->survey ? $survey->survey->{'surveytitle'} : null,
                "survey_type" => $survey->survey ? $survey->survey->{'surveytype'} : null,
                "survey_description" => $survey->survey ? $survey->survey->{'surveydescription'} : null,
            ];
        }

        return response()->json([
            'data' => $surveyList
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 400);
    }
}

}