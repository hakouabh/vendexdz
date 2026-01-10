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
        Schema::create('order_inconfirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oid');
            $table->foreignId('fsid');
            $table->foreign('oid')->references('oid')->on('orders')->onDelete('cascade');
            $table->foreign('fsid')->references('fsid')->on('first_step_status')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_inconfirmations');
    }
};
