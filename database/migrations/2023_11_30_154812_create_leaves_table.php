<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('admins');

            $table->unsignedBigInteger('leave_type');
            $table->foreign('leave_type')->references('id')->on('leave_types');

            // Date fields
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            // Text fields
            $table->text('reasons')->nullable();
            $table->text('remark')->nullable();

            // Boolean/integer fields
            $table->integer('is_half_day')->default(0);
            $table->integer('approved_status');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
