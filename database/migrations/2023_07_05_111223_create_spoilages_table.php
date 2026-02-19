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
        Schema::create('spoilages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('spoilage_order_number')->unique();
            $table->integer('spoilage_in')->default(2)->comment('1=> Warehouse, 2=>Store');
            $table->integer('is_return')->default(0)->comment('1=> yes, 0=>no');
            $table->unsignedBigInteger('from_warehouse_id')->nullable();
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses');
            $table->unsignedBigInteger('from_store_id')->nullable();
            $table->foreign('from_store_id')->references('id')->on('stores');
            $table->unsignedBigInteger('to_supplier_id')->nullable();
            $table->foreign('to_supplier_id')->references('id')->on('users');
            $table->unsignedBigInteger('to_warehouse_id')->nullable();
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses');
            $table->dateTime('spoilage_date');
            $table->integer('verified_person');
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('round_off_amount', 15, 2)->nullable()->default(0);
            $table->decimal('adjustment_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('total_expense_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_expense_billable_amount', 15, 2)->nullable()->default(0);
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->tinyInteger('is_notification_send_to_admin')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
            $table->integer('status')->nullable()->default(1);
            $table->integer('is_active')->nullable()->default(1)->comment('1=>active, 0=>inactive');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('admins');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('admins');
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
        Schema::dropIfExists('spoilages');
    }
};
