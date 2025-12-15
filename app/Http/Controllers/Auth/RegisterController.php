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
        'first_name' => 'nullable|string|max:50',
        'last_name' => 'nullable|string|max:50',
        'salt1' => 'required|string',
        'salt2' => 'required|string',
        'password_verifier' => 'required|string',
        'encrypted_vault' => 'required|string',
        'encrypted_master_key' => 'required|string',
    ]);

    User::create($validated);

    return response()->json(['status' => 'ok'], 201);
    }
}