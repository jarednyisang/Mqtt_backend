<?php

namespace App\Services;

use App\Models\TBUsers;
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