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
        Schema::create('sales_order_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sales_order_id');
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->string('sales_invoice_number')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('users');
            // $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_ir_id')->nullable();
            $table->foreign('warehouse_ir_id')->references('id')->on('warehouse_indent_requests');
            // $table->foreign('warehouse_ir_id')->references('id')->on('warehouse_indent_requests')->onDelete('cascade');
            $table->string('sales_invoice')->nullable();
            $table->string('attachments')->nullable();
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
        Schema::dropIfExists('sales_order_invoices');
    }
};