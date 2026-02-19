<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_return_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sales_order_return_id');
            $table->foreign('sales_order_return_id')->references('id')->on('sales_order_returns')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            $table->decimal('per_unit_price', 15, 2)->nullable()->default(0);
            $table->decimal('quantity', 8, 3)->nullable();
            $table->integer('unit_id')->nullable();
            $table->decimal('tax_value', 8, 2)->default(0)->nullable();
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0)->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_return_details');
    }
};
