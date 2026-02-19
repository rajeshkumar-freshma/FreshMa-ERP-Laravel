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
        Schema::create('staff_advance_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_advance_id')->nullable();
            $table->foreign('staff_advance_id')->references('id')->on('staff_advances');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id')->references('id')->on('admins');
            $table->integer('type')->comment('1 => Credit, 2 => Debit')->default(1);
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->longText('note')->nullable();
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('staff_advance_histories');
    }
};
