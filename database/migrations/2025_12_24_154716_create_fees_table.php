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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sid');
            $table->foreignId('3PLid');
            $table->foreignId('wid');

            $table->foreign('sid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('3PLid')->references('3PLid')->on('couriers')->onDelete('cascade');
            $table->foreign('wid')->references('wid')->on('willayas')->onDelete('cascade');

            $table->integer('o_s_p')->default(0);
            $table->integer('c_s_p')->default(0);
            $table->integer('o_d_p')->default(0);
            $table->integer('c_d_p')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
