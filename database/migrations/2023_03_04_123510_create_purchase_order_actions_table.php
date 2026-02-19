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
        Schema::create('purchase_order_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->tinyInteger('status');
            $table->tinyInteger('actioned_user_type')->comment('1=> admin, 2=> supplier');
            $table->unsignedBigInteger('action_by_user_id')->nullable();
            $table->foreign('action_by_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('action_by_admin_id')->nullable();
            $table->foreign('action_by_admin_id')->references('id')->on('admins');
            $table->dateTime('action_date')->nullable();
            $table->string('remarks', 500)->nullable();
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
        Schema::dropIfExists('purchase_order_actions');
    }
};
