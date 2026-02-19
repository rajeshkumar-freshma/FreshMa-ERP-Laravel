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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_account');
            $table->decimal('available_balance', 15, 2);
            $table->decimal('transaction_amount', 15, 2);
            $table->dateTime('transaction_date');
            $table->integer('transaction_type');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_account')->references('id')->on('accounts');
            // $table->foreign('transaction_account')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
