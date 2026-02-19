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
        Schema::create('purchase_order_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('purchase_order_return_number')->unique();
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->unsignedBigInteger('from_warehouse_id')->nullable();
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses');
            // $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('to_supplier_id')->nullable();
            $table->foreign('to_supplier_id')->references('id')->on('users');
            // $table->foreign('to_supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('return_type');
            $table->dateTime('ordered_date');
            $table->dateTime('return_date');
            $table->integer('return_authorised_person');
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('total_expense_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_expense_billable_amount', 15, 2)->nullable()->default(0);
            $table->decimal('round_off_amount', 15, 2)->nullable()->default(0);
            $table->decimal('adjustment_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->longText('remarks')->nullable();
            $table->integer('status')->nullable()->default(1);
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_returns');
    }
};
