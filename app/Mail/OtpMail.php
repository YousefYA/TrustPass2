<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    /**
     * Create a new message instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verify your TrustPass Account')
                    ->html("
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body style='margin: 0; padding: 0; background-color: #f3f4f6; font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;'>
            
            <div style='width: 100%; padding: 40px 0;'>
                
                <div style='max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);'>
                    
                    <div style='background-color: #4f46e5; padding: 30px; text-align: center;'>
                        <div style='margin-bottom: 10px;'>
                            <svg width='40' height='40' viewBox='0 0 24 24' fill='none' stroke='#ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                <path d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'></path>
                            </svg>
                        </div>
                        <h1 style='color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 1px;'>TRUSTPASS</h1>
                        <p style='color: #e0e7ff; margin: 5px 0 0; font-size: 13px; font-weight: 500;'>Zero-Knowledge Security</p>
                    </div>

                    <div style='padding: 40px 30px;'>
                        <p style='margin: 0 0 15px; font-size: 16px; color: #374151; line-height: 1.5;'>Hello,</p>
                        <p style='margin: 0 0 25px; font-size: 16px; color: #4b5563; line-height: 1.5;'>
                            We received a request to verify your email address. Use the code below to complete your secure registration:
                        </p>

                        <div style='background-color: #f9fafb; border: 2px dashed #e5e7eb; border-radius: 12px; padding: 25px; text-align: center; margin-bottom: 25px;'>
                            <span style='display: block; font-family: monospace; font-size: 32px; font-weight: 700; color: #111827; letter-spacing: 8px;'>{$this->code}</span>
                        </div>

                        <p style='margin: 0; font-size: 14px; color: #6b7280; text-align: center;'>
                            This code will expire in <strong style='color: #4b5563;'>10 minutes</strong>.
                        </p>
                    </div>

                    <div style='background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;'>
                        <p style='margin: 0; font-size: 12px; color: #9ca3af;'>
                            If you did not request this code, please ignore this email. Your account remains secure.
                        </p>
                        <p style='margin: 10px 0 0; font-size: 12px; color: #d1d5db;'>
                            &copy; 2025 TrustPass Security
                        </p>
                    </div>

                </div>
            </div>

        </body>
        </html>
                    ");
    }
}