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
        Schema::create('machine_sales_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('live_sales_bill_id');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            // $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->integer('bill_no')->nullable();
            $table->integer('total_discount_type')->nullable();
            $table->decimal('total_discount_amount', 8, 2)->nullable()->default(0);
            $table->decimal('total_discount_percentage', 8, 2)->nullable()->default(0);
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->dateTime('sale_datetime')->nullable();
            $table->integer('refund_count')->nullable();
            $table->decimal('refund_amount', 8, 2)->nullable();
            $table->decimal('refund_rounding', 8, 2)->nullable();
            $table->decimal('by_cash', 8, 2)->nullable();
            $table->decimal('by_voucher', 8, 2)->nullable();
            $table->string('by_voucher_no')->nullable();
            $table->decimal('by_cheque', 8, 2)->nullable();
            $table->string('by_cheque_no')->nullable();
            $table->decimal('by_credit_card', 8, 2)->nullable();
            $table->string('by_credit_card_no')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('report_name')->nullable();
            $table->string('kotreference')->nullable();
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
        Schema::dropIfExists('machine_sales_bills');
    }
};
