<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('warehouse_indent_request_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('warehouse_ir_id');
                $table->foreign('warehouse_ir_id')->references('id')->on('warehouse_indent_requests');
                // $table->foreign('warehouse_ir_id')->references('id')->on('warehouse_indent_requests')->onDelete('cascade');
                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products');
                // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->string('sku_code')->nullable();
                $table->string('name');
                $table->unsignedBigInteger('unit_id')->nullable();
                $table->foreign('unit_id')->references('id')->on('units');
                $table->decimal('request_quantity', 8,3)->nullable();
                $table->decimal('given_quantity', 8,3)->nullable();
                $table->decimal('remaining_quantity', 8,3)->nullable();
                $table->unsignedBigInteger('tax_id')->nullable();
                $table->foreign('tax_id')->references('id')->on('tax_rates');
                // $table->foreign('tax_id')->references('id')->on('tax_rates')->onDelete('cascade');
                $table->decimal('tax_value', 8, 2)->default(0);
                $table->integer('discount_type')->nullable()->comment('1 => fixed, 2=> percentage')->default(1);
                $table->decimal('discount_percentage', 8, 2)->nullable();
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->decimal('amount', 15, 2)->default(0);
                $table->decimal('sub_total', 15, 2)->default(0);
                $table->integer('added_by_requestor')->nullable()->comment('1 => yes, 0=> no');
                $table->integer('status')->default(1);
                $table->string('remarks')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }

        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('warehouse_indent_request_details');
    }
};
