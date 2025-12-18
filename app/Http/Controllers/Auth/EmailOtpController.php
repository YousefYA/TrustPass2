<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; // ✅ For sending email
use App\Mail\OtpMail;                // ✅ The email template we created

class EmailOtpController extends Controller
{
    /**
     * Send OTP to email
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $code = random_int(100000, 999999);

        // 🔒 Invalidate previous unused OTPs
        DB::table('email_otps')
            ->where('email', $request->email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // ✅ Create a new OTP
        DB::table('email_otps')->insert([
            'email' => $request->email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 📩 Send Real Email via Brevo
        try {
            Mail::to($request->email)->send(new OtpMail($code));
        } catch (\Exception $e) {
            // If email fails, return error so we know why
            return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }

        return response()->json(['status' => 'otp_sent']);
    }

    /**
     * Verify OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        $otp = DB::table('email_otps')
            ->where('email', $request->email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('id')
            ->first();

        if (
            !$otp ||
            !hash_equals($otp->code_hash, hash('sha256', $request->code))
        ) {
            return response()->json([
                'error' => 'Invalid or expired OTP',
            ], 401);
        }

        // ✅ Mark OTP as used
        DB::table('email_otps')
            ->where('id', $otp->id)
            ->update(['used_at' => now()]);

        // ✅ Session proof
        session(['otp_verified_email' => $request->email]);

        return response()->json(['status' => 'verified']);
    }
}