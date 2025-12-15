<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // STEP 1: return salt
    public function init(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 404);
        }

        return response()->json([
            'salt1' => $user->salt1,
        ]);
    }

    // STEP 2: verify proof
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

        return response()->json([
            'status' => 'ok',
            'user_id' => $user->id,
        ]);
    }
}