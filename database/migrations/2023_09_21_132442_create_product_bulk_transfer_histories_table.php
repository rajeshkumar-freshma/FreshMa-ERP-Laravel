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
        Schema::create('product_bulk_transfer_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_bulk_transfer_id');
            $table->dateTime('transfer_created_date')->nullable();
            $table->foreign('product_bulk_transfer_id')->references('id')->on('product_bulk_transfers')->onDelete('cascade');
            $table->json('product_bulk_transfer_data')->nullable();
            $table->json('product_bulk_transfer_details_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bulk_transfer_histories');
    }
};
