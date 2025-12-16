<?php

namespace App\Http\Controllers;
use App\Models\TBUsers;
use App\Models\TBCountries;
use App\Models\TBSurvey;
use App\Models\TBSurveyResponse;
use Illuminate\Http\Request;
use App\Services\CreatingService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;



use Carbon\Carbon;

class AdminController
{
  protected $creatingService;
    public function __construct(CreatingService $creatingService)
    {
        $this->creatingService = $creatingService;
    }
    public function store_surveys(Request $request)
    {
    
        try {
           $pid = @Session::get('pid')?:null;

      if ($pid == null) {
            return redirect('/')->with('error', 'Session expired. Please log in again.');
                    }
            // Validate request
            try{
            $this->creatingService->validateSurveys($request);
        } catch (\Exception $e) {
                        return redirect()->back()->with('error', 'Missing: ' . $e->getMessage());

        
        }
            try{

  
  
            $this->creatingService->createSurvey($request);
        } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to create Survey: ' . $e->getMessage());

         
        }
        return redirect('admindashboard')->with('success', 'Survey Created successfully.');

    
         
    
        } catch (Exception $e) {
    
            return redirect()->back()->with('error', 'Failed to created: ' . $e->getMessage());

            
        }
    }

public function viewsurvey($id)
{ 
    $pid = @Session::get('pid')?:null;

      if ($pid == null) {
            return redirect('/')->with('error', 'Session expired. Please log in again.');
                    }
    $getadmin = TBUsers::where(['pid'=> $pid,'usertype'=> 2])->first();
    if ($getadmin == null) {
       return redirect('/')->with('error', 'Not details. Please log in again.');
                  }
      $fullname=$getadmin->{'fullname'};
    $email=$getadmin->{'email'};
  $getsurveyresponses = TBSurveyResponse::where(['survey_id'=> $id])->orderBy('id', 'desc')->get(); 


    return view('viewsurvey',compact('getsurveyresponses','fullname','email'));

}



public function admindashboard()
{ 
    $pid = @Session::get('pid')?:null;

      if ($pid == null) {
            return redirect('/')->with('error', 'Session expired. Please log in again.');
                    }
    $getadmin = TBUsers::where(['pid'=> $pid,'usertype'=> 2])->first();
    if ($getadmin == null) {
       return redirect('/')->with('error', 'Not details. Please log in again.');
                  }
      $fullname=$getadmin->{'fullname'};
    $email=$getadmin->{'email'};
  $getsurveys = TBSurvey::orderBy('id', 'desc')->get(); 


    return view('admindashboard',compact('getsurveys','fullname','email'));

}




public function systemusers()
{ 
    $pid = @Session::get('pid')?:null;

      if ($pid == null) {
            return redirect('/')->with('error', 'Session expired. Please log in again.');
                    }
    $getadmin = TBUsers::where(['pid'=> $pid,'usertype'=> 2])->first();
    if ($getadmin == null) {
       return redirect('/')->with('error', 'Not details. Please log in again.');
                  }
      $fullname=$getadmin->{'fullname'};
    $email=$getadmin->{'email'};
  $getusers = TBUsers::with('country')
        ->orderBy('id', 'desc')
        ->get(); 


    return view('systemusers',compact('getusers','fullname','email'));

}
}