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
        Schema::create('partnership_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('partnership_name');
            $table->string('partnership_percentage');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('partnership_types');
    }
};
