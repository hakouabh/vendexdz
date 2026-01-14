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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->string('var_1')->nullable()->change();
            $table->string('var_2')->nullable()->change();
            $table->string('var_3')->nullable()->change();
            $table->unsignedBigInteger('product_id')->after('id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('sku')->after('id');
            $table->string('var_1')->nullable(false)->change();
            $table->string('var_2')->nullable(false)->change();
            $table->string('var_3')->nullable(false)->change();
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
};
