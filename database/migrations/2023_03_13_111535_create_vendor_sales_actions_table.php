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
        Schema::create('vendor_sales_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_sale_id');
            $table->foreign('vendor_sale_id')->references('id')->on('vendor_sales')->onDelete('cascade');
            $table->tinyInteger('status');
            $table->unsignedBigInteger('action_by')->nullable();
            $table->foreign('action_by')->references('id')->on('admins');
            $table->dateTime('action_date')->nullable();
            $table->string('remarks', 500)->nullable();
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
        Schema::dropIfExists('vendor_sales_actions');
    }
};
