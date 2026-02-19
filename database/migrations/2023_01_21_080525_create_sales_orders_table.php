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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number')->unique();
            $table->integer('sales_from')->comment('1=> Store, 2=>warehouse');
            $table->integer('sales_type')->comment('1=> Machine Sale, 2=>ERP Sale');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')->references('id')->on('users');
            // $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('quatation_id')->nullable();
            $table->foreign('quatation_id')->references('id')->on('quatations');
            // $table->foreign('quatation_id')->references('id')->on('quatations')->onDelete('cascade');
            $table->string('bill_no')->nullable();
            $table->dateTime('expected_payment_date')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->foreign('machine_id')->references('id')->on('machine_data');
            $table->integer('sales_person')->nullable();
            // $table->string('bill_to_street')->nullable();
            // $table->string('bill_to_city')->nullable();
            // $table->string('bill_to_state')->nullable();
            // $table->string('bill_to_country')->nullable();
            // $table->string('bill_to_zipcode')->nullable();
            // $table->integer('is_have_ship_address')->default(0);
            // $table->integer('show_ship_details')->default(0);
            // $table->string('ship_to_street')->nullable();
            // $table->string('ship_to_city')->nullable();
            // $table->string('ship_to_state')->nullable();
            // $table->string('ship_to_country')->nullable();
            // $table->string('ship_to_zipcode')->nullable();
            $table->dateTime('delivered_date');
            // $table->double('no_of_days_can_be_use')->nullable();
            $table->integer('is_inc_exp_billable_for_all')->nullable()->comment('1 => yes, 0=> no')->default(0);
            $table->integer('discount_type')->nullable()->comment('1 => fixed, 2=> percentage')->default(1);
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_request_quantity', 8,3)->nullable();
            $table->decimal('total_given_quantity', 8,3)->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('total_expense_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_expense_billable_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_commission_amount', 15, 2)->nullable()->default(0);
            $table->decimal('round_off_amount', 15, 2)->nullable()->default(0);
            $table->decimal('adjustment_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->tinyInteger('is_notification_send_to_admin')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->string('invoice')->nullable();
            $table->string('invoice_path')->nullable();
            $table->integer('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('admins');
            // $table->foreign('approved_by')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins');
            // $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade');
            $table->integer('status')->default(1);
            $table->integer('payment_status')->default(1)->comment('1=>paid, 2=>unpaid, 3=> due');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('sales_orders');
    }
};
