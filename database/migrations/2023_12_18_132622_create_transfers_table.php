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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_reason')->nullable();
            $table->unsignedBigInteger('from_account_id');
            $table->unsignedBigInteger('to_account_id');
            $table->decimal('available_balance', 15, 2);
            $table->decimal('transfer_amount', 15, 2);
            $table->dateTime('transaction_date'); // Assuming 'date' was meant to be 'transaction_date'
            $table->text('notes')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('from_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('from_account_id')->references('id')->on('accounts');
            // $table->foreign('to_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('to_account_id')->references('id')->on('accounts');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
