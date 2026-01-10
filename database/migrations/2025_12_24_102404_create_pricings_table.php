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
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cid');
            $table->foreign('cid')->references('cid')->on('categories')->onDelete('cascade');
            $table->integer('qmin');
            $table->integer('qmax');
            $table->integer('price');
            $table->integer('qb');
            $table->integer('usell');
            $table->integer('csell');
            $table->integer('ab_o');
            $table->integer('msg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricings');
    }
};
