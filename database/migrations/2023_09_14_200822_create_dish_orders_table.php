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
        Schema::create('dish_order', function (Blueprint $table) {
            $table->unsignedBigInteger('dish_id');
            $table->Foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');

            $table->unsignedBigInteger('order_id');
            $table->Foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->smallInteger('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_order');
    }
};
