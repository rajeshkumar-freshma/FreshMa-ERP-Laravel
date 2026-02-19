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
        Schema::create('live_sales_bill_details', function (Blueprint $table) {
            $table->id();
            $table->integer('live_sales_bill_id')->nullable();
            $table->integer('billNo')->nullable();
            $table->string('pluName')->nullable();
            $table->decimal('wtQty', 8, 3)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('taxLinkNo')->nullable();
            $table->integer('discntType')->nullable();
            $table->decimal('discntVal', 8, 2)->nullable();
            $table->string('uom')->nullable();
            $table->string('opName')->nullable();
            $table->integer('MachineName')->nullable();
            $table->dateTime('Date')->nullable();
            $table->string('reportName')->nullable();
            $table->string('pluNumber')->nullable();
            $table->string('pluCode')->nullable();
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
        Schema::dropIfExists('live_sales_bill_details');
    }
};
