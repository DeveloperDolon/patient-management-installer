<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('special_procedures', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->text('procedure');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_procedures');
    }
};
