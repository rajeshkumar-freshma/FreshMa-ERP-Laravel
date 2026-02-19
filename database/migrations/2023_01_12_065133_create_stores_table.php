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
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('store_name')->unique();
            $table->string('slug')->unique();
            $table->string('store_code');
            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            // $table->integer('store_category');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->string('gst_number')->nullable();
            $table->text('address')->nullable();
            $table->integer('city_id');
            $table->integer('state_id');
            $table->integer('country_id');
            $table->string('pincode');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->polygon('coordinates')->nullable();
            $table->longText('direction')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('stores');
    }
};
