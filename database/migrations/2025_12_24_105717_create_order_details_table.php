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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oid');    
            $table->foreign('oid')->references('oid')->on('orders')->onDelete('cascade');
            $table->boolean('stopdesk')->default('0');
            $table->integer('price')->default('0');
            $table->integer('delivery_price')->default('0');
            $table->integer('total')->default('0');
            $table->string('commenter')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
