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
        Schema::create('denominations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('denomination_id')->nullable();
            $table->foreign('denomination_id')->references('id')->on('denomination_types');
            $table->integer('denomination_value')->default(0);
            $table->integer('count')->default(0);
            $table->unsignedBigInteger('cash_register_transaction_id')->nullable();
            $table->foreign('cash_register_transaction_id')
                ->references('id')
                ->on('cash_register_transactions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->foreign('cash_register_id')->references('id')->on('cash_registers');
            $table->unsignedBigInteger('cash_paid_id')->nullable();
            $table->foreign('cash_paid_id')->references('id')->on('cash_paid_to_offices');
            $table->integer('amount');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
	        $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denominations');
    }
};
