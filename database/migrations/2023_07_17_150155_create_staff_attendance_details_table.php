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
        Schema::create('staff_attendance_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_attendance_id')->nullable();
            $table->foreign('staff_attendance_id')->references('id')->on('staff_attendances');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id')->references('id')->on('admins');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->dateTime('in_time')->nullable();
            $table->dateTime('out_time')->nullable();
            $table->tinyInteger('is_present')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attandance_details');
    }
};
