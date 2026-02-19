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
        Schema::create('mis_matching_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mis_matching_adjustment_id');

            // Specify a shorter name for the foreign key constraint
            $table->foreign('mis_matching_adjustment_id', 'fk_mis_adj_details_mis_adj_id')
                ->references('id')->on('mis_matching_adjustments')->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->integer('type')->comment('1 => Addition, 2 => Subtraction')->default(1);
            $table->decimal('quantity', 15, 3);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mis_matching_adjustment_details');
    }
};
