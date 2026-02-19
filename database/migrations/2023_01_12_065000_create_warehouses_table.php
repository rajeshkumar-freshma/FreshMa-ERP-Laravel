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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('code');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->longText('address')->nullable();
            $table->integer('city_id');
            $table->integer('state_id');
            $table->integer('country_id');
            $table->string('pincode');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->longText('direction')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_default')->nullable()->default(0);
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('admins');
            // $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('admins');
            // $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('warehouses');
    }
};
