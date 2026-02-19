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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_type')->default(1)->comment('1 => Purchase, 2 => Sales, 3 => return, 4 => store, 5=>expense, 6=>useradvance, 7=>product_transfers,8=>income');
            $table->integer('type')->comment('1 => Credit, 2 => Debit')->default(1);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->decimal('amount', 15, 2)->default(0);
            $table->dateTime('transaction_datetime')->nullable();
            $table->string('transaction_number', 191)->nullable();
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('admins');
            // $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('payment_transations');
    }
};
