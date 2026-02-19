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
        Schema::create('sales_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_sale_id')->nullable();
            $table->foreign('vendor_sale_id')->references('id')->on('vendor_sales');
            // $table->foreign('vendor_sale_id')->references('id')->on('vendor_sales')->onDelete('cascade');
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->foreign('sales_order_id')->references('id')->on('sales_orders');
            // $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->unsignedBigInteger('store_sale_id')->nullable();
            $table->foreign('store_sale_id')->references('id')->on('store_sales');
            // $table->foreign('store_sale_id')->references('id')->on('store_sales')->onDelete('cascade');
            $table->unsignedBigInteger('income_expense_id');
            $table->foreign('income_expense_id')->references('id')->on('income_expense_types');
            // $table->foreign('income_expense_id')->references('id')->on('income_expense_types')->onDelete('cascade');
            $table->decimal('ie_amount', 15, 2)->default(0);
            $table->integer('is_billable')->nullable()->default(0);
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('admins');
            // $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('admins');
            // $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('sales_expenses');
    }
};
