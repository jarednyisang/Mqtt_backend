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
use App\Models\TBPriceList;

use Illuminate\Support\Facades\Hash;



use Carbon\Carbon;

class HomeApiController
{

    protected $creatingService;
    public function __construct(CreatingService $creatingService)
    {
        $this->creatingService = $creatingService;
    }





public function store_newuser(Request $request)
{
    try {
        // Validate request
        try {
            $this->creatingService->validateRegistrationRequest($request);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Missing data: ' . $e->getMessage(),
            ], 400);
        }

        // Register user
        try {
            $referalcode = $request->get('refcode');

            if ($referalcode != null) {
                $trytoretrieve = TBUsers::where('code', $referalcode)->first();

                if (!$trytoretrieve) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Kindly use an accurate and correct referral code.',
                    ], 400);
                } else {
                    // increment pendingreferral by 1
                    TBUsers::where('code', $referalcode)->increment('pendingreferal', 1);
                }
            }

            $this->creatingService->createUser($request);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create User: ' . $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'error' => false,
            'message' => 'Welcome! Your account has been created. Sign in now.'
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 400);
    }
}

    public function loginApi(Request $request)
    {    
        try {
            $username = $request->input('Username');
            $password = $request->input('Password');
            
            if (!$username) {
                return response(['error' => true, 'message' => 'Email missing'], 400);
            }
            
            if (!$password) {
                return response(['error' => true, 'message' => 'Password missing'], 400);
            }
    
            $rec = TBUsers::getWhere(["email" => $username], true);

            if ($rec && isset($rec->password1)) {
                if (hash('sha256', $password) === $rec->password1) {
                    return response([
                        "error" => false,
                        "message" => "Success!",
                        "pid" => $rec->pid,
                         "countryid" => $rec->countryid,
                        "fullname" => $rec->fullname ?? "",
                        "email" => $rec->email ?? "",
                         "password" => $password,
                         "code" => $rec->code,

                    ], 200);
                }
            
                return response([
                    "error" => true,
                    "message" => "Invalid credentials, try again!"
                ], 400);
            }
            
            
    
            return response([
                "error" => true,
                "message" => "Credentials not found in our systems!"
            ], 400);
            
        } catch (\Exception $e) {
            return response([
                "error" => true,
                "message" => "Error: " . $e->getMessage(),
                "line" => $e->getLine()
            ], 500);
        }
    }



  public function get_countries()
  {
      try {
  
          $countries = TBCountries::orderBy('id', 'asc')->get();

          $countrieslist = [];
  
          foreach($countries as $country) {
              $countrieslist[] = [
                  "id" => $country->{'id'},
                  "country" => $country->{'country_name'},
              ];
          }
  
  
         return response()->json([
             'data' => $countrieslist
         ], 200);
  
      } catch (\Exception $e) {
          return response()->json([
              'error' => true,
              'message' => $e->getMessage()
          ], 400);
      }
  }


  public function get_pricelist()
  {
      try {
  
        $price = TBPriceList::where('id', 1)->first();
          $priceAmount=$price->{'amount'};
  
         return response()->json([
             'amountData' => $priceAmount
         ], 200);
  
      } catch (\Exception $e) {
          return response()->json([
              'error' => true,
              'message' => $e->getMessage()
          ], 400);
      }
  }


    
}