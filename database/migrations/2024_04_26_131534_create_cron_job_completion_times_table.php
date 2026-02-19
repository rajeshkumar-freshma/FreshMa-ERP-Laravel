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
        Schema::create('cron_job_completion_times', function (Blueprint $table) {
            $table->id();
            $table->string('cron_job_name');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->tinyInteger('status')->comment('1=>Success,2=>Failure');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_job_completion_times');
    }
};
