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
        Schema::create('store_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aid')->constrained('users')->onDelete('cascade'); // Store ID
            $table->foreignId('sid')->constrained('users')->onDelete('cascade'); // Manager ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_agents');
    }
};
