<?php

namespace App\Http\Controllers;
use App\Models\TBUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendMessageMail;
use Carbon\Carbon;
class EmailController extends Controller
{
   
     public function sendPasswordMessage(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'TOEMAIL' => 'required|email'
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }
    $toemail = $request->get('TOEMAIL');
      $checkuser = TBUsers::where('email', $toemail)->first();

        if (!$checkuser) {
            return response()->json([
                'error' => true,
                'message' => 'Email provided not found.'
            ], 400);
        }
    $usersname=$checkuser->fullname;

    $randomNumber = rand(10000, 99999);
   $expirytime = Carbon::now()->addMinutes(10);

    $subject = "Reset Password";
   $message = "Hello, $usersname! This is your OTP: <strong> $randomNumber</strong>. It will expire within 10 minutes.";
    $sendername = "Silicon Highland Ltd";
    $senderemail = "noreply@siliconhighland.com";
    
        try {

             $updated = TBUsers::where([
                'email' => $toemail,
               
            ])->update([
                'otp_reset' => $randomNumber,
                'otp_expiry' => $expirytime,
            ]);
            // Send the email
            Mail::to($toemail)->send(
                new SendMessageMail( 
                    $subject,
                    $message, 
                    $sendername,
                    $senderemail
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendBulkMessages(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array',
            'emails.*' => 'email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'sender_name' => 'nullable|string|max:255',
            'sender_email' => 'nullable|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $successCount = 0;
            $failedEmails = [];

            foreach ($request->emails as $email) {
                try {
                    Mail::to($email)->send(
                        new SendMessageMail(
                            $request->subject,
                            $request->message,
                            $request->sender_name,
                            $request->sender_email
                        )
                    );
                    $successCount++;
                } catch (\Exception $e) {
                    $failedEmails[] = [
                        'email' => $email,
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully sent {$successCount} emails",
                'sent_count' => $successCount,
                'failed_count' => count($failedEmails),
                'failed_emails' => $failedEmails
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send bulk emails',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}