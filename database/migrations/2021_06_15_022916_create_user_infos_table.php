<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_type')->comment('1=> admin, 2=> user');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->string('currency_id')->nullable();
            $table->string('gst_number')->nullable();
            $table->dateTime('joined_at')->nullable();
            $table->string('image')->nullable();
            $table->string('image_path')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('pan_file')->nullable();
            $table->string('pan_file_path')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->string('aadhar_file')->nullable();
            $table->string('aadhar_file_path')->nullable();
            $table->string('esi_number')->nullable();
            $table->string('esi_file')->nullable();
            $table->string('esi_file_path')->nullable();
            $table->string('pf_number')->nullable();
            $table->string('pf_file')->nullable();
            $table->string('pf_file_path')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('name_as_per_record')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_passbook_file')->nullable();
            $table->string('bank_passbook_file_path')->nullable();
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
        Schema::dropIfExists('user_infos');
    }
}
