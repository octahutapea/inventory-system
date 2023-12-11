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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('acquisition_value_id');
            $table->string('item_area');
            $table->string('item_location');
            $table->string('serial_number');
            $table->string('item_pict')->nullable();
            $table->string('item_status');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('acquisition_value_id')->references('id')->on('acquisition_values')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
