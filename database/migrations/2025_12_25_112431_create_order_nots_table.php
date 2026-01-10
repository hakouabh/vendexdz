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
        Schema::create('order_nots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oid');
            $table->foreignId('uid');
            $table->foreign('oid')->references('oid')->on('orders')->onDelete('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
            $table->string('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_nots');
    }
};
