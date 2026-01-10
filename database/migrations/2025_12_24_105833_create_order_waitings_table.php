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
        Schema::create('order_waitings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oid');
            $table->foreignId('asid');
            $table->foreign('oid')->references('oid')->on('orders')->onDelete('cascade');
            $table->foreign('asid')->references('asid')->on('accept_step_status')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_waitings');
    }
};
