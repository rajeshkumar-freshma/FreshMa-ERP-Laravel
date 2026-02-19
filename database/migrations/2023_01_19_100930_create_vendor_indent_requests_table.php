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
        Schema::create('vendor_indent_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('request_code');
            $table->dateTime('request_date');
            $table->dateTime('expected_date');
            $table->integer('status');
            $table->decimal('discount_percentage', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_request_quantity', 8,3);
            $table->decimal('sub_total', 15, 2)->default(0);
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
        Schema::dropIfExists('vendor_indent_requests');
    }
};
