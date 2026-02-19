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
        Schema::create('cash_register_transaction_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            $table->unsignedBigInteger('crt_id')->nullable();
            $table->foreign('crt_id')->references('id')->on('cash_register_transactions');
            $table->integer('payment_category_id')->nullable();
            $table->dateTime('attachment_date')->nullable();
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('attached_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_register_transaction_documents');
    }
};
