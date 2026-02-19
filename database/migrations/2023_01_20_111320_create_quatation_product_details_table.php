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
        Schema::create('quatation_product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quatation_id');
            $table->foreign('quatation_id')->references('id')->on('quatations');
            // $table->foreign('quatation_id')->references('id')->on('quatations')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            // $table->string('gross_weight')->nullable();
            // $table->string('net_weight')->nullable();
            // $table->string('no_of_pieces')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            $table->decimal('per_unit_price', 15, 2)->nullable()->default(0);
            $table->decimal('quantity', 8, 3)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedBigInteger('tax_id')->nullable();
            // $table->foreign('tax_id')->references('id')->on('tax_rates')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('tax_rates');
            $table->decimal('tax_value', 8, 2)->default(0)->nullable();
            $table->integer('discount_type')->nullable()->comment('1 => fixed, 2=> percentage')->default(1);
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0)->nullable();
            $table->decimal('total', 15, 2)->default(0);
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
        Schema::dropIfExists('quatation_product_details');
    }
};
