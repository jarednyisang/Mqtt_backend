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

class HomeController
{


    public function loginweb(Request $request)
{
    try{

        $username = $request->input('email');
        $password = $request->input('password');
        if ( isset($username) && isset($password)){
            $rec = TBUsers::getWhere(["email" => $username],true);

            if (isset($rec->{'password1'})){
                if ($rec->{'password1'} == hash('sha256',$password)) {
                    $pid = $rec->{"pid"};

                    Session::put("pid",$pid);
                   if ($rec->{'usertype'} == 2) {
                        return redirect()->intended('admindashboard');
                    }
                       else{
                                    return redirect('/')->with('error', 'You cant acess! website is not working.');

                         
                           } 
                   
                }
         return redirect('/')->with('error', 'Invalid credentials try again!.');

              
            }
  return redirect('/')->with('error', 'Credentials not found in our systems!.');

       
        }
          return redirect('/')->with('error', 'Enter required details!!.');

     
    }catch (Exception $e){
        return response([
            "error" => true,
            "message" => "Error! ".$e->getMessage(),"Line".$e->getLine()
        ]);
    }
}



public function settings()
{ 
    $pid = @Session::get('pid')?:null;

      if ($pid == null) {
                        return redirect()->intended('/');
                    }
    $getuserdetails = TBUsers::where('pid', $pid)->first();
    $fullname=$getuserdetails->{'fullname'};
    $email=$getuserdetails->{'email'};
 



    return view('settings',compact('fullname','email'));

}


public function index()
{ 
   
    $countries = TBCountries::orderBy('id', 'asc')->get();
  $getsurveys = TBSurvey::orderBy('id', 'desc')->take(4)->get(); 

    return view('welcome',compact('countries','getsurveys'));

}






}