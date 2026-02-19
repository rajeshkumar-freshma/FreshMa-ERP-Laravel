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
        Schema::create('store_stock_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('from_warehouse_id')->nullable();
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->dateTime('stock_update_on');
            $table->decimal('existing_stock', 15, 3);
            $table->decimal('adding_stock', 15, 3);
            $table->decimal('total_stock', 15, 3);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('reference_table')->nullable()->comment('1 => Purchase, 2 => Sales, 3 => return, 4 => store, 5=>expense, 6=>product Transfers, 7=>ReDistribution, 8=>Adjustment, 9=>Store Indent Request,10=>Fish Cutting,11=>Bulk Product Transafer,12=>Daily Stock Update,13=>Spoilage,14=>Live Machine Sales');
            $table->tinyInteger('status');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('admins');
            // $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('admins');
            // $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('store_stock_updates');
    }
};
