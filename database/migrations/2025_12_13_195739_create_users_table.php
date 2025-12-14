<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('email')->unique();

            // Zero-knowledge authentication verifier
            $table->text('password_verifier');

            // Salts
            $table->string('salt1');
            $table->string('salt2');

            // Encrypted blobs
            $table->longText('encrypted_vault');
            $table->longText('encrypted_master_key');

            // Optional metadata
            $table->string('mfa_type')->nullable();
            $table->string('device_fingerprint')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};