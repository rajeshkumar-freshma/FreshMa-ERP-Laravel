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
        Schema::create('spoilage_product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spoilage_id');
            $table->foreign('spoilage_id')->references('id')->on('spoilages')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            $table->decimal('per_unit_price', 15, 2)->nullable()->default(0);
            $table->decimal('quantity', 8, 3)->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
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
        Schema::dropIfExists('spoilage_product_details');
    }
};
