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
        Schema::create('user_roles', function (Blueprint $table) {
        $table->unsignedBigInteger('uid'); 
        $table->unsignedBigInteger('rid'); 
        
        $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('rid')->references('rid')->on('roles')->onDelete('cascade');

 
        $table->primary(['uid', 'rid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
