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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('loan_code')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->integer('loan_type_id');
            $table->unsignedBigInteger('loan_category_id');
            $table->dateTime('distributed_date');
            $table->dateTime('applied_on');
            $table->dateTime('first_payment_date');
            $table->decimal('applied_amount', 15, 2);
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('repayment_amount', 15, 2);
            $table->string('phone_number');
            $table->integer('repayment_frequency');
            $table->decimal('late_payment_penalty_rate', 5, 2);
            $table->integer('loan_officer')->nullable();
            // disburse_method cash or gpay or card
            $table->integer('disburse_method');
            $table->text('disburse_notes');
            $table->tinyInteger('deduct_form_salary');
            $table->integer('loan_term');
            $table->integer('loan_tenure');
            $table->tinyInteger('loan_status');
            $table->decimal('interest_rate', 5, 2);
            $table->integer('interest_frequency');
            $table->integer('guarantors')->nullable();
            $table->string('documents')->nullable();
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->foreign('employee_id')->references('id')->on('admins');
            // $table->foreign('employee_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('company_loan_bank_account_details');
            // $table->foreign('bank_id')->references('id')->on('company_loan_bank_account_details')->onDelete('cascade');
            $table->foreign('loan_category_id')->references('id')->on('loan_categories');
            // $table->foreign('loan_category_id')->references('id')->on('loan_categories')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
