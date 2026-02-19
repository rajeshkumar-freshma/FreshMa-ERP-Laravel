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
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('phone_number')->unique();
            $table->integer('otp')->nullable();
            $table->string('user_code', 191)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('user_type')->comment('admin, manager, partner');
            $table->integer('role_id')->default(1);
            $table->string('fcm_token')->nullable();
            $table->string('os')->nullable()->comment('1=iOS, 2=Android');
            $table->longText('voipToken')->nullable();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->integer('status')->default(1)->comment('0= inactive,1= active');
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
};
