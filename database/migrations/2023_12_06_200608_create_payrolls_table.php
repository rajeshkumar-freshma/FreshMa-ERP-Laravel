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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // Assuming it's a foreign key
            $table->integer('month');
            $table->unsignedSmallInteger('year'); // Assuming a small integer is enough for a year
            $table->decimal('gross_salary', 15, 3); // Assuming 10 digits in total, 2 after the decimal point
            $table->text('remarks')->nullable(); // Use text for longer remarks, and allow it to be nullable
            $table->tinyInteger('status'); // Assuming it's a small integer representing status
            $table->tinyInteger('loss_of_pay_days')->default(0); // Default to 0 if not provided
            $table->tinyInteger('no_of_working_days')->default(0); // Default to 0 if not provided
            $table->timestamps();
            $table->softDeletes();
            // Foreign key constraint for employee_id
            $table->foreign('employee_id')->references('id')->on('admins')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
