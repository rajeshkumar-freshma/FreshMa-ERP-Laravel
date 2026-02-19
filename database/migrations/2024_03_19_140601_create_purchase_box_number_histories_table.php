<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_box_number_histories', function (Blueprint $table) {
            $table->id();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->unsignedBigInteger('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->integer('quantity');
            $table->integer('box_no');
            $table->integer('type')->comment('1 => Addition, 2 => Subtraction');
            $table->dateTime('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_box_number_histories');
    }
};
