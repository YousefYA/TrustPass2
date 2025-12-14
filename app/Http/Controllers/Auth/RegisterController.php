<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        logger()->info('REGISTER PAYLOAD', $request->all());

        $validated = $request->validate([
        'email' => 'required|email|unique:users,email',
        'salt1' => 'required',
        'salt2' => 'required',
        'password_verifier' => 'required',
        'encrypted_vault' => 'required',
        'encrypted_master_key' => 'required',
    ]);

        User::create([
            'email' => $validated['email'],
            'salt1' => $validated['salt1'],
            'salt2' => $validated['salt2'],
            'password_verifier' => $validated['password_verifier'],
            'encrypted_vault' => $validated['encrypted_vault'],
            'encrypted_master_key' => $validated['encrypted_master_key'],
        ]);

        return response()->json(['status' => 'ok'], 201);
    }
}