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
        Schema::create('product_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aid');
            $table->string('sku');
            $table->foreign('aid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('portion');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producat_agents');
    }
};
