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
        Schema::create('staff_advances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id')->references('id')->on('admins');
            $table->integer('type')->comment('1 => Credit, 2 => Debit')->default(1);
            $table->decimal('amount', 15, 2)->default(0);
            $table->integer('payment_type_id');
            $table->dateTime('date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_advances');
    }
};
