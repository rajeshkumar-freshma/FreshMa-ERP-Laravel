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
        Schema::create('quatations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sales_estimation_number')->unique();
            $table->string('sales_for')->nullable()->comment('1=> Store, 2=>Vendor');
            $table->integer('sales_from')->nullable()->comment('1=> Store, 2=>warehouse');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            // $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')->references('id')->on('users');
            // $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('to_store_id')->nullable();
            $table->foreign('to_store_id')->references('id')->on('stores');
            // $table->foreign('to_store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->integer('sales_person')->nullable();
            $table->integer('status')->default(1);
            $table->string('remarks')->nullable();
            $table->tinyInteger('is_notification_send_to_admin')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
            $table->integer('created_by')->comment('1=> admin,2=> user/vendor');;
            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->foreign('created_user_id')->references('id')->on('users');
            // $table->foreign('created_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('created_admin_id')->nullable();
            $table->foreign('created_admin_id')->references('id')->on('admins');
            // $table->foreign('created_admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->dateTime('created_date')->nullable();
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
        Schema::dropIfExists('quatations');
    }
};
