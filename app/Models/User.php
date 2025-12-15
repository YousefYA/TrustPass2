<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;
  protected $fillable = [
    'email',
    'first_name',
    'last_name',
    'salt1',
    'salt2',
    'password_verifier',
    'encrypted_vault',
    'encrypted_master_key',
    ];
}