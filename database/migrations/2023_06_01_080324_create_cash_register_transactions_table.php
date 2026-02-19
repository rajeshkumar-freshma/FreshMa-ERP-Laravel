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
        Schema::create('cash_register_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->foreign('cash_register_id')->references('id')->on('cash_registers');
            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->decimal('amount', 15, 2)->default(0);
            $table->dateTime('transaction_datetime')->nullable();
            $table->integer('type')->comment('1 => Credit, 2 => Debit')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('cash_register_transactions');
    }
};
