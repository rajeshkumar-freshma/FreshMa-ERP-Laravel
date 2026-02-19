<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fish_cutting_product_maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_product_id')->nullable();
            $table->foreign('main_product_id')->references('id')->on('products');
            $table->longText('grouped_product')->nullable();
            $table->decimal('wastage_percentage', 15, 2)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins');
            $table->longText('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fish_cutting_product_maps');
    }
};
