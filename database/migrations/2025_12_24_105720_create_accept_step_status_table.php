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
        Schema::create('accept_step_status', function (Blueprint $table) {
            $table->foreignId('asid')->primary();
            $table->string('name');
            $table->string('icon');
            $table->string('color');
            $table->boolean('show')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accept_step_status');
    }
};
