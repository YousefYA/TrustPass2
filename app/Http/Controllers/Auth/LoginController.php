<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\OtpMail;

class LoginController extends Controller
{
    /**
     * Step 1: Init - Get Salts
     */
    public function init(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'salt1' => $user->salt1,
            'salt2' => $user->salt2
        ]);
    }

    /**
     * Step 2: Check Password (Zero-Knowledge) & Send OTP
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verifier' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        // 1. Verify the Zero-Knowledge Proof
        if (!$user || $user->password_verifier !== $request->verifier) {
             return response()->json(['error' => 'Invalid password'], 401);
        }

        // 2. Password is correct! Now Send MFA OTP.
        $code = random_int(100000, 999999);

        // Invalidate old OTPs
        DB::table('email_otps')
            ->where('email', $request->email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Insert new OTP
        DB::table('email_otps')->insert([
            'email' => $request->email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send Email
        try {
            Mail::to($request->email)->send(new OtpMail($code));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send OTP'], 500);
        }

        return response()->json(['status' => 'otp_sent']);
    }

    /**
     * Step 3: Finalize Login (Check Session)
     */
    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // 🔒 The Session Bridge Check
        if (session('otp_verified_email') !== $request->email) {
            return response()->json(['error' => 'MFA not verified'], 403);
        }

        $user = User::where('email', $request->email)->first();
        
        Auth::login($user);
        
        session()->forget('otp_verified_email');
        session()->regenerate();

        return response()->json([
            'status' => 'logged_in',
            'user' => $user,
            'encrypted_vault' => $user->encrypted_vault
        ]);
    }
}