<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_expense_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->comment('1 => Income, 2 => Expenseer')->default(1);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('attached_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_expense_documents');
    }
};
