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
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id');
            $table->unsignedBigInteger('payroll_type_id');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('payroll_id')->references('id')->on('payrolls');
            $table->foreign('payroll_type_id')->references('id')->on('payroll_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
