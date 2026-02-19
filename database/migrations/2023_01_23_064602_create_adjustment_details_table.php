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
        Schema::create('adjustment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('adjustment_id');
            $table->foreign('adjustment_id')->references('id')->on('adjustments')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            $table->integer('type')->comment('1 => Addition , 2 => Subtraction');
            $table->decimal('quantity', 8, 3)->nullable();
            $table->decimal('stock', 8, 3)->nullable();
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
        Schema::dropIfExists('adjustment_details');
    }
};
