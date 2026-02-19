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
        Schema::create('sales_order_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sales_order_return_number')->unique();
            $table->integer('return_from')->comment('1=> Store, 2=>Vendor');
            $table->unsignedBigInteger('sales_order_id')->nullable();
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->unsignedBigInteger('to_warehouse_id')->nullable();
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses');
            // $table->foreign('to_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('from_store_id')->nullable();
            $table->foreign('from_store_id')->references('id')->on('stores');
            // $table->foreign('from_store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedBigInteger('from_vendor_id')->nullable();
            $table->foreign('from_vendor_id')->references('id')->on('users');
            // $table->foreign('from_vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('return_type')->nullable()->comment('1=>full, 2=>partially');
            $table->dateTime('return_date');
            $table->integer('return_authorised_person');
            $table->decimal('sub_total', 15, 2)->nullable()->default(0);
            $table->decimal('total_expense_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_expense_billable_amount', 15, 2)->nullable()->default(0);
            $table->decimal('round_off_amount', 15, 2)->nullable()->default(0);
            $table->decimal('adjustment_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_amount', 15, 2)->nullable()->default(0);
            $table->longText('remarks')->nullable();
            $table->tinyInteger('is_notification_send_to_admin')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
            $table->integer('status')->nullable()->default(1);
            $table->integer('payment_status')->default(1)->comment('1=>paid, 2=>unpaid, 3=> due');
            $table->integer('is_active')->nullable()->default(1)->comment('1=>active, 0=>inactive');
            $table->integer('is_same_day_return')->nullable()->default(1)->comment('1=>yes, 0=>no');
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
        Schema::dropIfExists('sales_order_returns');
    }
};
