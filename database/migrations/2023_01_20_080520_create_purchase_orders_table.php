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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('purchase_order_number')->unique();
            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('users');
            // $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_ir_id')->nullable();
            $table->foreign('warehouse_ir_id')->references('id')->on('warehouse_indent_requests');
            // $table->foreign('warehouse_ir_id')->references('id')->on('warehouse_indent_requests')->onDelete('cascade');
            $table->dateTime('delivery_date');
            $table->double('no_of_days_can_be_use')->nullable();
            $table->integer('status');
            $table->integer('payment_status')->default(2)->comment('1=>paid, 2=>unpaid, 3=> due');
            $table->integer('is_inc_exp_billable_for_all')->nullable();
            $table->decimal('total_request_quantity', 8, 3)->nullable();
            // $table->decimal('total_discount_amount', 8, 2)->default(0);
            $table->integer('discount_type')->nullable()->comment('1 => fixed, 2=> percentage')->default(1);
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('adjustment_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_tax', 8, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('total_expense_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_expense_billable_amount', 15, 2)->nullable()->default(0);
            $table->tinyInteger('is_notification_send_to_admin')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('admins');
            // $table->foreign('approved_by')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins');
            // $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_orders');
    }
};
