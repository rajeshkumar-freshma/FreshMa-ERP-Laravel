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
        Schema::create('spoilage_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spoilage_id');
            $table->foreign('spoilage_id')->references('id')->on('spoilages');
            $table->unsignedBigInteger('income_expense_id');
            $table->foreign('income_expense_id')->references('id')->on('income_expense_types');
            $table->decimal('ie_amount', 15, 2)->default(0);
            $table->integer('is_billable')->nullable()->default(0);
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('admins');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('admins');
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
        Schema::dropIfExists('spoilage_expenses');
    }
};
