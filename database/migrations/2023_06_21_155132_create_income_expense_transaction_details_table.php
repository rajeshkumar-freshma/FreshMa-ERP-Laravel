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
        Schema::create('income_expense_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ie_transaction_id');
            $table->foreign('ie_transaction_id')->references('id')->on('income_expense_transactions');
            $table->unsignedBigInteger('ie_type_id');
            $table->foreign('ie_type_id')->references('id')->on('income_expense_types');
            // $table->foreign('ie_type_id')->references('id')->on('income_expense_types')->onDelete('cascade');
            $table->string('others_name')->nullable();
            $table->integer('employee_id')->nullable();
            $table->longText('remarks')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
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
        Schema::dropIfExists('income_expense_transaction_details');
    }
};
