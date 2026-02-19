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
        Schema::create('loan_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->decimal('amount',15,3);
            $table->integer('loan_tenure');
            $table->integer('loan_term');
            $table->integer('loan_term_method');
            $table->decimal('interest_rate', 5, 2);
            $table->integer('interest_type');
            $table->integer('interest_frequency');
            $table->integer('repayment_frequency');
            $table->decimal('late_payment_penalty_rate', 5, 2);
            $table->json('charges')->default(null);
            $table->tinyInteger('status');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_categories');
    }
};
