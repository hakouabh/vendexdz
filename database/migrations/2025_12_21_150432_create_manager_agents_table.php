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
        Schema::create('manager_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mid')->constrained('users')->onDelete('cascade'); // Store ID
            $table->foreignId('aid')->constrained('users')->onDelete('cascade'); // Manager ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_agents');
    }
};
