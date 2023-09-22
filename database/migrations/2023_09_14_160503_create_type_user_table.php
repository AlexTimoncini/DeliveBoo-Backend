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
        Schema::create('type_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->Foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('type_id');
            $table->Foreign('type_id')->references('id')->on('types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_user');
    }
};
