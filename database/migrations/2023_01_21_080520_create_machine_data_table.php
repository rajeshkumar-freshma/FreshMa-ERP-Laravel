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
        Schema::create('machine_data', function (Blueprint $table) {
            $table->id();
            $table->integer('Slno')->nullable();
            $table->string('MachineName');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            $table->string('IPAddress')->nullable();
            $table->integer('Port');
            $table->string('Status')->nullable();
            $table->string('Capacity')->nullable();
            $table->string('PLUMasterCode')->nullable();
            $table->integer('SelectAll')->nullable();
            $table->integer('Online')->nullable();
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
        Schema::dropIfExists('machine_data');
    }
};
