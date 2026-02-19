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
        Schema::create('purchase_order_return_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_order_return_id');
            $table->foreign('purchase_order_return_id')->references('id')->on('purchase_order_returns')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            // $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->decimal('per_unit_price', 15, 2)->nullable()->default(0);
            $table->decimal('quantity', 8, 3)->nullable();
            $table->decimal('tax_value', 8, 2)->default(0);
            $table->decimal('discount_percentage', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->integer('is_billable')->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('purchase_order_return_details');
    }
};
