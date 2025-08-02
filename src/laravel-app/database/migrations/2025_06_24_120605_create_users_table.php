<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('phone')->unique();
            $table->string('address')->nullable();
            $table->string('password');
            $table->text('remember_token')->nullable();
            $table->enum('role', ['admin', 'doctor'])->default('doctor');
            $table->timestamps();
        });
    }

    /** password123
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
