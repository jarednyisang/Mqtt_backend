<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
class SendMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject; // Public properties are automatically available in your Blade view
    public $mailMessage; // Using a different name to avoid conflict with Mailable's own $message property if any
    public $senderName;
    public $senderEmail;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $mailMessage
     * @param string|null $senderName
     * @param string|null $senderEmail
     * @return void
     */
public function __construct(string $subject, string $mailMessage, ?string $senderName = null, ?string $senderEmail = null)
    {
     

        $this->subject = $subject;
        $this->mailMessage = $mailMessage;
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;

        // Sets the email subject for the actual email headers
        $this->subject($subject);

        // Set the 'from' address if provided
        if ($senderEmail) {
            $this->from($senderEmail, $senderName ?? config('app.name'));
        }
    }


    /**
     * Build the message.
     *
     * @return $this
     */
 // app/Mail/SendMessageMail.php
public function build()
{
    return $this->html("
        <!DOCTYPE html>
        <html>
        <head>
            <title>{$this->subject}</title>
        </head>
        <body style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>

            <!-- App Logo (round) -->
            <div style='text-align: center; margin-bottom: 20px;'>
                <img src='http://ecommerce.siliconhighland.com/images/classicpos1.png' 
                     alt='Classic POS Logo' 
                     width='120' 
                     style='border-radius: 50%; display: block; margin: 0 auto;'>
            </div>

            <p>{$this->mailMessage}</p>

            <p><strong>Classic POS</strong></p>

            " . ($this->senderName ? "<p>Regards,</p><p>{$this->senderName}</p>" : "") . "
            " . ($this->senderEmail ? "<p>Email: siliconhighlandltd@gmail.com</p>" : "") . "

            <p>Website: <a href='https://siliconhighland.com/' target='_blank'>siliconhighland.com</a></p>

            <!-- Footer with reduced height -->
            <div style='margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; text-align: center;'>
                <p style='font-size: 12px; color: #666;'>Powered by Silicon Highland Ltd</p>
                <img src='http://ecommerce.siliconhighland.com/images/Silicon_Highland_Footer.jpg' 
                     alt='Company Logo' 
                     style='width: 100%; max-width: 100%; object-fit: cover; display: block; margin: 0 auto;'>
            </div>

        </body>
        </html>
    ");
}



}