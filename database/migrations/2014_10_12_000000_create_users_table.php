<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 191);
            $table->string('last_name', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('password', 191);
            $table->string('phone_number')->unique();
            $table->string('user_code', 191)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->tinyInteger('user_type')->nullable();
            $table->string('api_token', 80)->nullable()->default(null);
            $table->string('fcm_token')->nullable();
            $table->longText('address1')->nullable();
            $table->longText('address2')->nullable();
            $table->longText('address3')->nullable();
            $table->longText('voipToken')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->string('os')->nullable();
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
        Schema::dropIfExists('users');
    }
}
