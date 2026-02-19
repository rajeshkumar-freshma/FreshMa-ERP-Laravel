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
        Schema::create('product_bulk_transfer_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_bulk_transfer_id');
            $table->foreign('product_bulk_transfer_id')->references('id')->on('product_bulk_transfers')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            // $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->decimal('per_unit_price', 15, 2)->nullable()->default(0);
            // $table->decimal('quantity', 8, 3)->nullable();
            $table->decimal('request_quantity', 8,3)->nullable();
            $table->decimal('given_quantity', 8,3)->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->integer('is_inc_exp_billable')->nullable();
            $table->decimal('expense_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bulk_transfer_details');
    }
};
