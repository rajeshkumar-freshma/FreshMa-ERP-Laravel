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
        Schema::create('store_daily_checklist_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_daily_checklist_id')->nullable();
            $table->foreign('store_daily_checklist_id')->references('id')->on('store_daily_checklists');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->string('checklist');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_daily_checklist_details');
    }
};
