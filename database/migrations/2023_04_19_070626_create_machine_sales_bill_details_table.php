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
        Schema::create('machine_sales_bill_details', function (Blueprint $table) {
            $table->id();
            $table->integer('machine_sales_bill_id');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            // $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            $table->decimal('wt_qty', 8, 3)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('tax_rates');
            // $table->foreign('tax_id')->references('id')->on('tax_rates')->onDelete('cascade');
            $table->decimal('tax_value', 8, 2)->nullable()->default(0);
            $table->integer('discount_type')->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable()->default(0);
            $table->decimal('discount_percentage', 8, 2)->nullable()->default(0);
            $table->string('uom')->nullable();
            $table->string('operator_ame')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('report_name')->nullable();
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
        Schema::dropIfExists('machine_sales_bill_details');
    }
};
