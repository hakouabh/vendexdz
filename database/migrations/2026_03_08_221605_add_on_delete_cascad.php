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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });

        Schema::table('user_stores', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('installed_apps', function (Blueprint $table) {
            $table->dropForeign(['sid']);
            $table->foreign('sid')->references('id')->on('stores')->onDelete('cascade');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->foreign('store_id')->references('id')->on('stores');
        });  
        Schema::table('user_stores', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->foreign('store_id')->references('id')->on('stores');
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });  
        Schema::table('installed_apps', function (Blueprint $table) {
            $table->dropForeign(['sid']);
            $table->foreign('sid')->references('id')->on('stores');
        });  
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')->references('id')->on('users');
        });  
    }
};
