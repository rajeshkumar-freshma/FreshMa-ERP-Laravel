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
        Schema::create('payment_transaction_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('reference_table')->nullable()->comment('1=>purchase, 2=>sales, 3=>expense');
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('attached_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transaction_documents');
    }
};
