<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concomitant_diseases', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->enum('category', ['medical', 'surgical', 'gynae $ obst']);
            $table->text('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concomitant_diseases');
    }
};
