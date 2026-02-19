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
        Schema::create('warehouse_stock_update_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_update_history_id');
            $table->foreign('stock_update_history_id')->references('id')->on('warehouse_stock_updates')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            // $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->decimal('old_stock', 15,3);
            $table->decimal('new_stock', 15,3);
            $table->string('old_box_number')->nullable();
            $table->string('new_box_number')->nullable();
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
        Schema::dropIfExists('warehouse_stock_update_histories');
    }
};
