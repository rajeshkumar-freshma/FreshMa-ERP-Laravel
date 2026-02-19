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
        Schema::create('sales_order_multi_transactions', function (Blueprint $table) {
            $table->id();
            $table->json('sales_order_id')->nullable();
            $table->integer('customer_id');
            $table->decimal('amount', 15, 2)->default(0)->nullable();
            $table->integer('advance_amount_included')->default(0)->nullable();
            $table->decimal('advance_amount', 15, 2)->default(0)->nullable();
            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->dateTime('transaction_date')->nullable();
            $table->longText('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_multi_transactions');
    }
};
