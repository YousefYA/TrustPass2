<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; // ✅ REQUIRED
use App\Mail\OtpMail;                // ✅ REQUIRED

class EmailOtpController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $code = random_int(100000, 999999);

        // Invalidate old OTPs
        DB::table('email_otps')
            ->where('email', $request->email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Create new OTP
        DB::table('email_otps')->insert([
            'email' => $request->email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ❌ OLD LOGGING CODE (Remove this line)
        // logger()->info("OTP for {$request->email}: {$code}");

        // ✅ NEW SENDING CODE (Add this line)
        try {
             Mail::to($request->email)->send(new OtpMail($code));
        } catch (\Exception $e) {
             return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'otp_sent']);
    }

    // ... (Keep your verify function exactly as it is) ...
    public function verify(Request $request)
    {
        // (Paste your existing verify logic here)
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

        if (!$otp || !hash_equals($otp->code_hash, hash('sha256', $request->code))) {
            return response()->json(['error' => 'Invalid or expired OTP'], 401);
        }

        DB::table('email_otps')->where('id', $otp->id)->update(['used_at' => now()]);
        session(['otp_verified_email' => $request->email]);

        return response()->json(['status' => 'verified']);
    }
}