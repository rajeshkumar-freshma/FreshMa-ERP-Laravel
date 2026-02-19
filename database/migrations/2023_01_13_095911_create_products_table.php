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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            // $table->string('sku_code')->unique()->nullable();
            $table->string('sku_code')->nullable();
            $table->string('hsn_code')->nullable();
            // $table->string('gross_weight')->nullable();
            // $table->string('net_weight')->nullable();
            // $table->string('no_of_pieces')->nullable();
            $table->string('product_description')->nullable();
            $table->string('image')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('item_type_id')->nullable();
            $table->foreign('item_type_id')->references('id')->on('item_types')->onDelete('cascade');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('tax_rates')->onDelete('cascade');
            $table->tinyInteger('tax_type')->nullable()->comment('1=> inclusive, 2=>exclusive');
            $table->tinyInteger('status')->default(1)->comment('1=> active, 2=> Inactive');
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
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
        Schema::dropIfExists('products');
    }
};
