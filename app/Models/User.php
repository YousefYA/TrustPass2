<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'password_verifier',
        'salt1',
        'salt2',
        'encrypted_vault',
        'encrypted_master_key',
    ];
}