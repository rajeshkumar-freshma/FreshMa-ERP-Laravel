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
        Schema::create('purchase_order_qc_checks_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            $table->decimal('total_purchased_quantity', 8,3)->nullable();
            $table->decimal('total_received_quantity', 8,3)->nullable();
            $table->decimal('good_quantity', 8,3)->nullable();
            $table->decimal('issued_quantity', 8,3)->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('can_be_sale')->default(1)->comment('1=> can be sale, 0=> need to return');
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
        Schema::dropIfExists('purchase_order_qc_checks_details');
    }
};
