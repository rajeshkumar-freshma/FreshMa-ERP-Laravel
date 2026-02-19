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
        Schema::create('units', function (Blueprint $table) {
            // $table->bigIncrements('id');
            // $table->string('unit_name');
            // $table->string('unit_short_code')->comment('kg, lt, pc');
            // $table->string('base_unit')->comment('meter, kilogram, liter');
            // $table->integer('allow_decimal', 10, 2)->nullable();
            // $table->integer('default')->default(0);
            // $table->string('operator')->nullable()->comment('1=> plus, 2=> subtractor, 3=> multiplication, 4=> divide');
            // $table->integer('operation_value', 10, 2)->comment('1 Kg => 1000 grams, 1000 is operation_value')->nullable();
            // $table->tinyInteger('status');
            // $table->unsignedBigInteger('created_by');
            // $table->foreign('created_by')->references('id')->on('admins');
            // $table->unsignedBigInteger('updated_by');
            // $table->foreign('updated_by')->references('id')->on('admins');
            // $table->timestamps();
            // $table->softDeletes();
            $table->bigIncrements('id');
            $table->string('unit_name');
            $table->string('unit_short_code')->comment('kg, lt, pc');
            $table->string('base_unit')->comment('meter, kilogram, liter');
            $table->decimal('allow_decimal', 10, 2)->nullable();
            $table->integer('default')->default(0);
            $table->string('operator')->nullable()->comment('1=> plus, 2=> subtractor, 3=> multiplication, 4=> divide');
            $table->decimal('operation_value', 10, 2)->nullable()->comment('1 Kg => 1000 grams, 1000 is operation_value');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('units');
    }
};
