<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_sales_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('live_sales_bill_id');
            $table->integer('billNo')->nullable();
            $table->integer('opLinkNo')->nullable();
            $table->integer('tDiscntType')->nullable();
            $table->decimal('tDiscntVal', 8, 2)->nullable();
            $table->decimal('totalMoney', 8, 2)->nullable();
            $table->dateTime('ItemsaleDateTime')->nullable();
            $table->integer('refundCnt')->nullable();
            $table->decimal('refundAmt', 8, 2)->nullable();
            $table->decimal('refundRounding', 8, 2)->nullable();
            $table->decimal('byCash', 8, 2)->nullable();
            $table->decimal('byVoucher', 8, 2)->nullable();
            $table->string('byVoucherNo')->nullable();
            $table->decimal('byCheque', 8, 2)->nullable();
            $table->string('byChequeNo')->nullable();
            $table->decimal('byCreditCard', 8, 2)->nullable();
            $table->string('byCreditCardNo')->nullable();
            $table->integer('MachineName')->nullable();
            $table->dateTime('Date')->nullable();
            $table->string('reportName')->nullable();
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
        Schema::dropIfExists('live_sales_bills');
    }
};
