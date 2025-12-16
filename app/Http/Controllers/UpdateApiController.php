<?php

namespace App\Http\Controllers;

use App\Models\TBUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Services\CreatingService;



use Carbon\Carbon;

class UpdateApiController
{
    protected $creatingService;
    public function __construct(CreatingService $creatingService)
    {
        $this->creatingService = $creatingService;
    }





    public function update_password( Request $request)
    {
        try {
            $userpid = $request->get('PID');
            $Newpassword = $request->get('NEWPASSWORD1');
            if (!$Newpassword) {
                return response(['error' => true, 'message' => 'New Password missing'], 400);
            }
           
    
            $updated = TBUsers::where([
                'pid' => $userpid
            ])->update([
                "password1" => hash('sha256', $Newpassword),
                "password2" => hash('sha256', $Newpassword),
              
            ]);
    
            if ($updated === 0) {
                return response()->json([
                    'error' => false,
                    'message' => 'No changes made'
                ], 200);
            }
    
            return response()->json([
                'error' => false,
                'message' => 'Updated successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


      public function update_currency( Request $request)
    {
        try {
            $userpid = $request->get('PID');
              $currency = $request->get('CURRENCY');
            if (!$currency) {
                return response(['error' => true, 'message' => 'Currency missing'], 400);
            }
           
    
            $updated = TBUsers::where([
                'pid' => $userpid
            ])->update([
                "currency_symbol" => $currency,
              
            ]);
    
            if ($updated === 0) {
                return response()->json([
                    'error' => false,
                    'message' => 'No changes made'
                ], 200);
            }
    
            return response()->json([
                'error' => false,
                'message' => 'Updated successfully'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }





public function forgot_password(Request $request)
{
    try {
        $otp_password = $request->get('OTP');
        $Newpassword = $request->get('NEWPASSWORD1');
         $emailtosend = $request->get('EMAIL');
  if (!$otp_password) {
            return response(['error' => true, 'message' => 'OTP missing'], 400);
        }
        if (!$Newpassword) {
            return response(['error' => true, 'message' => 'New Password missing'], 400);
        }

         if (!$emailtosend) {
            return response(['error' => true, 'message' => 'Email missing'], 400);
        }

        $user = TBUsers::where('otp_reset', $otp_password)->where('email', $emailtosend)->first();

        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'OTP provided not found.'
            ], 400);
        }

        // Check if OTP is expired
        if ($user->otp_expiry && Carbon::now()->gt(Carbon::parse($user->otp_expiry))) {
            return response()->json([
                'error' => true,
                'message' => 'OTP has expired.'
            ], 400);
        }

        // Update password
        $updated = TBUsers::where('otp_reset', $otp_password)->update([
            "password1" => hash('sha256', $Newpassword),
            "password2" => hash('sha256', $Newpassword),
            "otp_reset" => null,
            "otp_expiry" => null,
        ]);

        if ($updated === 0) {
            return response()->json([
                'error' => false,
                'message' => 'No changes made'
            ], 200);
        }

        return response()->json([
            'error' => false,
            'message' => 'Password updated successfully'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'Failed to update password.',
            'details' => $e->getMessage(),
        ], 500);
    }
}







   
}