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
        Schema::create('fish_cutting_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fish_cutting_id')->nullable();
            $table->foreign('fish_cutting_id')->references('id')->on('fish_cuttings');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->decimal('slice', 15, 3)->default(0);
            $table->decimal('head', 15, 3)->default(0);
            $table->decimal('tail', 15, 3)->default(0);
            $table->decimal('eggs', 15, 3)->default(0);
            $table->decimal('wastage', 15, 3)->default(0);
            $table->dateTime('created_on')->nullable();
            $table->dateTime('updated_on')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins');
            $table->longText('grouped_product')->nullable();
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
        Schema::dropIfExists('fish_cutting_details');
    }
};
