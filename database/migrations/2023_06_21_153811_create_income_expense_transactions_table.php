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
        Schema::create('income_expense_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('expense_invoice_number')->unique();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            // $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            // $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->integer('income_expense_type_id')->nullable();
            $table->dateTime('transaction_datetime')->nullable();
            $table->integer('related_to')->comment('1=> store, 2=> warehouse');
            $table->integer('reference_id')->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('adjustment_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->integer('actioned_by')->nullable();
            $table->integer('user_type')->comment('1=> admin, 2=> user')->nullable(); // admin, user
            $table->tinyInteger('status')->default(0)->comment('1=> active, 0=> inactive')->nullable(); // admin, user
            $table->tinyInteger('payment_status')->default(1)->comment('1=> Paid, 2=> UnPaid, 3=> Pending/Due')->nullable(); // admin, user
            $table->tinyInteger('is_notification_send_to_user')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('income_expense_transactions');
    }
};
