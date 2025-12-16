<?php

namespace App\Services;

use App\Models\TBUsers;
use App\Models\TBSurvey;
use App\Models\TBSurveyResponse;
use App\Models\TBTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use Exception;

class CreatingService
{
    
    public function validateWithdrawal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'method'        => 'required|string|max:255',
            'amount'        => 'required|string|max:11',
          'account_details'  => 'required|string'  ,
         'pid'  => 'required|string'           
         

        ]);
    
        if ($validator->fails()) {
        return response()->json([
            'error' => true,
            'message' => $validator->errors()->first(),
        ], 400);
    }

    return null;
    }

    

   public function createWithdrawal(Request $request)
    {   
        $pid = $request->get('pid');
        $dateTime = Carbon::now();
        $lastRec = @TBTransactions::orderBy('id', "desc")->first();
        $id =  $lastRec ? $lastRec->{'id'} + 1 : 1;
        $method = $request->get('method');
         $Amount = $request->get('amount');
        $accountdetails = $request->get('account_details');
        
      
        $data = [
                "id" => $id,
                "date" => $dateTime,
                 "pid" => $pid,
                  "transaction_type" => "Withdraw",  
                 "method" => $method,  
                  "amount" => $Amount,
                 "account_details" => $accountdetails,
                "status" => 6,
                                 
                
        ];

         TBTransactions::create($data);
       
         
    }


      public function validateResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surveyId'        => 'required|string|max:255',
            'surveyAmount'        => 'required|string|max:255',
          'surveyFeedback'  => 'required|string' ,
                   'pid'  => 'required|string'           
          

        ]);
    
         if ($validator->fails()) {
        return response()->json([
            'error' => true,
            'message' => $validator->errors()->first(),
        ], 400);
    }

    return null;
    }

    

   public function createResponse(Request $request)
    {   
        $pid = $request->get('pid');
        $dateTime = Carbon::now();
        $lastRec = @TBSurveyResponse::orderBy('id', "desc")->first();
        $id =  $lastRec ? $lastRec->{'id'} + 1 : 1;
        $surveyid = $request->get('surveyId');
         $surveyAmount = $request->get('surveyAmount');
        $responsefeedback = $request->get('surveyFeedback');
        
      

        $data = [
                "id" => $id,
                "date" => $dateTime,
                 "pid" => $pid,
                 "survey_id" => $surveyid,  
                  "survey_amount" => $surveyAmount,
                 "response" => $responsefeedback,
                "status" => 6,
                                 
                
        ];

         TBSurveyResponse::create($data);
       
         
    }





     public function validateSurveys(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'        => 'required|string|max:255',
            'description'        => 'required|string|max:255',
          'type'  => 'required|string|max:255',
            'reward'  => 'required|string|max:255',
             'participants'  => 'required|string|max:255',
                      'pid'  => 'required|string'           


        ]);

       if ($validator->fails()) {
        return response()->json([
            'error' => true,
            'message' => $validator->errors()->first(),
        ], 400);
    }

    return null;
       
    }

    

   public function createSurvey(Request $request)
    {   
          $pid = @Session::get('pid')?:null;
        $dateTime = Carbon::now();
        $lastRec = @TBSurvey::orderBy('id', "desc")->first();
        $id =  $lastRec ? $lastRec->{'id'} + 1 : 1;
        $title = $request->get('title');
         $description = $request->get('description');
        $surveytype = $request->get('type');
        $rewardamount = $request->get('reward');
        $participant = $request->get('participants');
          $minutes = $request->get('minutes');
      

        $data = [
                "id" => $id,
                "date" => $dateTime,
                 "pid" => $pid,
                 "surveytitle" => $title,  
                  "surveydescription" => $description,
                 "surveytype" => $surveytype,
                 "minutes" => $minutes,
                  "rewardamount" => $rewardamount,
                  "participants" => $participant,
                "status" => 6,
                                 
                
        ];

         TBSurvey::create($data);
       
         
    }

   





    public function validateRegistrationRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'email'           => 'required|email|max:255|unique:systemusers,email',
            'password'        => 'required|string|min:4',
            'password_confirmation'  => 'required|same:password',
            'agreeTerms'  => 'required|string|max:255',

        ], [
            'email.unique' => 'Email already exists',
        ]);
    
         if ($validator->fails()) {
        return response()->json([
            'error' => true,
            'message' => $validator->errors()->first(),
        ], 400);
    }

    return null;
    }

    

   
    public function createUser(Request $request)
    {     
        $dateTime = Carbon::now();
        $lastRec = @TBUsers::orderBy('pid', "desc")->first();
        $pid =  $lastRec ? $lastRec->{'pid'} + 1 : 1;
        $userfullname = $request->get('name');
         $referalcode = $request->get('refcode');
        $useremail = $request->get('email');
        $countryid = $request->get('country');
         $currencySymbol ='USD';
        $userpassword1 = $request->get('password');
        $userpassword2 = $request->get('password_confirmation');
     $terms = $request->get('agreeTerms');
      $randomCode = Str::random(8);



        $data = [
                "date" => $dateTime,
                 "pid" => $pid,
                 "fullname" => $userfullname,  
                  "code" => $randomCode,
                 "usertype" => 1,
                 "email" => $useremail,
                  "countryid" => $countryid,
                  "currency_symbol" => $currencySymbol,
                   "referalcode" => $referalcode,
                   "ever_activated" => 1,
                  "subscription_enddate" => $dateTime,
                   "lifetimebalance" => 0,
                    "availablebalance" => 0,
                   "totalreferal" => 0,
                   "pendingreferal" => 0,
                "terms" => $terms,
                 "password1" => hash('sha256', $userpassword1),
                 "password2" => hash('sha256', $userpassword2),
                
        ];

         TBUsers::create($data);
       
         
    }


    }