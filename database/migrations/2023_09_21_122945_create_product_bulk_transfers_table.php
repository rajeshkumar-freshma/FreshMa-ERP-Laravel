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
        Schema::create('product_bulk_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transfer_order_number')->unique();
            $table->integer('transfer_from')->comment('1=> warehouse, 2=>Store')->default(1);
            $table->integer('transfer_to')->comment('1=> warehouse, 2=> Store')->default(2);
            $table->unsignedBigInteger('from_warehouse_id')->nullable();
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses');
            // $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('to_store_id')->nullable();
            $table->foreign('to_store_id')->references('id')->on('stores');
            // $table->foreign('to_store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedBigInteger('tap_id')->nullable()->comment('transfer authorized person'); // transfer authorized person
            $table->foreign('tap_id')->references('id')->on('admins');
            // $table->foreign('tap_id')->references('id')->on('admins')->onDelete('cascade');
            $table->dateTime('transfer_created_date')->nullable();
            $table->dateTime('transfer_received_date')->nullable();
            $table->integer('status')->default(1);
            $table->integer('converted_to_distribution')->default(0);
            $table->string('remarks')->nullable();
            $table->tinyInteger('is_notification_send_to_admin')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
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
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bulk_transfers');
    }
};
