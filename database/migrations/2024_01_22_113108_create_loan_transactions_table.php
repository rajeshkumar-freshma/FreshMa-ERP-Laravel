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
        Schema::create('loan_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->integer('type')->comment('1 => Credit, 2 => Debit')->default(1);
            $table->decimal('amount', 15, 2)->default(0);
            $table->dateTime('transaction_datetime')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->longText('note')->nullable();
            // $table->unsignedBigInteger('created_by');
            // $table->foreign('created_by')->references('id')->on('admins');
            // $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_transactions');
    }
};
