<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code; // This holds the OTP number

    /**
     * Create a new message instance.
     */
    public function __construct($code)
    {
        $this->code = $code; // Store the code so we can use it in the email
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verify your TrustPass Account')
                    ->html("
                        <div style='font-family: sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                            <h2 style='color: #4f46e5;'>TRUSTPASS</h2>
                            <p style='font-size: 16px; color: #333;'>Your verification code is:</p>
                            <h1 style='background: #f3f4f6; padding: 10px; border-radius: 5px; color: #111827; letter-spacing: 5px;'>
                                {$this->code}
                            </h1>
                            <p style='font-size: 14px; color: #666;'>This code expires in 10 minutes.</p>
                        </div>
                    ");
    }
}