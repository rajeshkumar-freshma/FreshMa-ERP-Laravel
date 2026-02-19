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
        Schema::create('purchase_sales_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->comment('1 => Purchase Order, 2 => Sales Order, 3 => Store Return, 4 => re-distribution, 5 => Spoilage, 6 => User Advance')->default(1);
            $table->unsignedBigInteger('reference_id')->nullable();
            // $table->unsignedBigInteger('purchase_order_id');
            // $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->integer('document_type')->nullable()->comment('1 => Expense, 2=> transporttracking');
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('attached_by')->nullable();
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
        Schema::dropIfExists('purchase_order_documents');
    }
};
