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
        Schema::create('cash_paid_to_offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            $table->decimal('amount', 15, 2)->default(0);
            $table->integer('payer_id')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->string('signature')->nullable();
            $table->string('signature_path')->nullable(); 
            $table->string('file')->nullable();
            $table->string('file_path')->nullable();
            $table->tinyInteger('is_notification_send_to_admin')->default(0)->comment('1=> yes, 0=> no')->nullable(); // admin, user
            $table->integer('status')->default(1); // 1=> Active, 0 => In-Active
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins');
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('cash_paid_to_offices');
    }
};
