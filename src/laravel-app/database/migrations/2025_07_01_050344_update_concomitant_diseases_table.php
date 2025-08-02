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
        Schema::table('concomitant_diseases', function (Blueprint $table) {
            $table->enum('category', ['medical', 'surgical', 'gynae & obst'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('concomitant_diseases', function (Blueprint $table) {
            $table->enum('category', ['medical', 'surgical', 'gynae $ obst'])->change();
        });
    }
};
