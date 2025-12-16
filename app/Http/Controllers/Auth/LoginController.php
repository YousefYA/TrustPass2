<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function init(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 404);
        }

        return response()->json([
            'salt1' => $user->salt1,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password_verifier' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            !$user ||
            !hash_equals($user->password_verifier, $request->password_verifier)
        ) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // ✅ SESSION
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['status' => 'ok']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => 'logged_out']);
    }
}