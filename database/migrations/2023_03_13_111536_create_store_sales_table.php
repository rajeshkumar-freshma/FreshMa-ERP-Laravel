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
        Schema::create('store_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sir_id')->nullable();
            $table->foreign('sir_id')->references('id')->on('store_indent_requests')->onDelete('cascade');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            // $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->string('store_sales_number');
            $table->dateTime('delivered_date');
            $table->integer('status');
            $table->integer('payment_status')->default(1)->comment('1=>paid, 2=>unpaid, 3=> due');
            $table->integer('is_inc_exp_billable_for_all')->nullable()->default(0);
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->decimal('total_request_quantity', 8,3);
            $table->decimal('total_given_quantity', 8,3);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('total_expense_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_commission_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->tinyInteger('is_notification_send_to_admin')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
            $table->integer('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('admins');
            // $table->foreign('approved_by')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by');
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
        Schema::dropIfExists('store_sales');
    }
};
