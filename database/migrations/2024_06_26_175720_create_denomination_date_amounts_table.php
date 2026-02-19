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
        Schema::create('denomination_date_amounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->foreign('cash_register_id')->references('id')->on('cash_registers');
            $table->unsignedBigInteger('cash_paid_id')->nullable();
            $table->foreign('cash_paid_id')->references('id')->on('cash_paid_to_offices');
            $table->string('dates');
            $table->float('amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denomination_date_amounts');
    }
};
