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
        Schema::create('user_advance_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('transaction_type')->default(1)->comment('1 => Purchase, 2 => Sales, 3 => return, 4 => store, 5=>expense');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('type')->comment('1 => Credit, 2 => Debit')->default(1);
            $table->decimal('amount', 15, 2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->longText('note')->nullable();
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('customer_advance_histories');
    }
};
