<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('payment_date');
            $table->unsignedBigInteger('loan_id');
            $table->string('invoice_number');
            $table->decimal('instalment_amount',8,2);
            $table->decimal('pay_amount',8,2);
            $table->decimal('due_amount',8,2);
            $table->foreign('loan_id')->references('id')->on('loans');
            // $table->foreign('loan_transaction_id')->references('id')->on('loan_transactions')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_repayments');
    }
};
