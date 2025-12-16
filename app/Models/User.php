<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password_verifier',
        'salt1',
        'salt2',
        'encrypted_vault',
        'encrypted_master_key',
        'first_name',
        'last_name',
        'mfa_type',
        'device_fingerprint',
    ];

    protected $hidden = [
        'password_verifier',
        'encrypted_master_key',
    ];
}