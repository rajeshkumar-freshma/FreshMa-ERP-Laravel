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
        Schema::create('transport_trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->unsignedBigInteger('vendor_sale_id')->nullable();
            $table->foreign('vendor_sale_id')->references('id')->on('vendor_sales');
            // $table->foreign('vendor_sale_id')->references('id')->on('vendor_sales')->onDelete('cascade');
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->foreign('sales_order_id')->references('id')->on('sales_orders');
            // $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->unsignedBigInteger('store_sale_id')->nullable();
            $table->foreign('store_sale_id')->references('id')->on('store_sales');
            // $table->foreign('store_sale_id')->references('id')->on('store_sales')->onDelete('cascade');
            $table->unsignedBigInteger('sales_order_return_id')->nullable();
            $table->foreign('sales_order_return_id')->references('id')->on('sales_order_returns');
            // $table->foreign('sales_order_return_id')->references('id')->on('sales_order_returns')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_order_return_id')->nullable();
            $table->foreign('purchase_order_return_id')->references('id')->on('purchase_order_returns');
            // $table->foreign('purchase_order_return_id')->references('id')->on('purchase_order_returns')->onDelete('cascade');
            $table->unsignedBigInteger('spoilage_id')->nullable();
            // $table->foreign('spoilage_id')->references('id')->on('spoilages');
            // $table->foreign('spoilage_id')->references('id')->on('spoilages')->onDelete('cascade');
            $table->unsignedBigInteger('product_transfer_id')->nullable();
            $table->foreign('product_transfer_id')->references('id')->on('product_transfers')->onDelete('cascade');
            $table->unsignedBigInteger('transport_type_id')->nullable();
            $table->foreign('transport_type_id')->references('id')->on('transport_types');
            $table->string('transport_name')->nullable();
            $table->string('transport_number')->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->string('phone_number')->nullable();
            $table->dateTime('departure_datetime')->nullable();
            $table->dateTime('arriving_datetime')->nullable();
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('creator_type')->comment('1=> admin, 2=> supplier');
            $table->unsignedBigInteger('creator_user_id')->nullable();
            $table->foreign('creator_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('creator_admin_id')->nullable();
            $table->foreign('creator_admin_id')->references('id')->on('admins');
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
        Schema::dropIfExists('transport_trackings');
    }
};
