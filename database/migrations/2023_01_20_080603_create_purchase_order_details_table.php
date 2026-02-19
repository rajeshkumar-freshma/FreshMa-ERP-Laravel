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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            // $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->decimal('per_unit_price', 15, 2)->nullable()->default(0);
            $table->integer('is_inc_exp_billable')->nullable();
            $table->decimal('inc_exp_amount', 15, 2)->default(0)->nullable();
            $table->decimal('request_quantity', 8,3)->nullable();
            $table->decimal('given_quantity', 8,3)->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedBigInteger('tax_id')->nullable();
            // $table->foreign('tax_id')->references('id')->on('tax_rates')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('tax_rates');
            $table->decimal('tax_value', 8, 2)->default(0)->nullable();
            $table->integer('discount_type')->nullable()->comment('1 => fixed, 2=> percentage')->default(1);
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0)->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->integer('added_by_supplier')->nullable()->comment('1 => yes, 0=> no');
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
        Schema::dropIfExists('purchase_order_details');
    }
};
