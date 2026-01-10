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
        Schema::create('stop_desks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('3PLid');
            $table->integer('code_postal');
            $table->foreign('code_postal')->references('code_postal')->on('towns')->onDelete('cascade');
            $table->foreign('3PLid')->references('3PLid')->on('couriers')->onDelete('cascade');
            $table->boolean('has_stop_desk')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stop_desks');
    }
};
