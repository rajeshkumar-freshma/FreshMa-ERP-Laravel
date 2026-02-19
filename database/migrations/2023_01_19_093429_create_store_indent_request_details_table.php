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
        Schema::create('store_indent_request_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_indent_request_id');
            $table->foreign('store_indent_request_id')->references('id')->on('store_indent_requests')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku_code')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            $table->decimal('request_quantity', 8,3)->nullable();
            $table->decimal('given_quantity', 8,3)->nullable();
            $table->integer('added_by_requestor')->nullable()->comment('1 => yes, 0=> no');
            $table->integer('status')->default(1);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('store_indent_request_details');
    }
};