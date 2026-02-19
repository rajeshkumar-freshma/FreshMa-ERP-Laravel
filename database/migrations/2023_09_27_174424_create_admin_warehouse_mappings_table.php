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
        Schema::create('admin_warehouse_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->dateTime('assigned_at');
            $table->string('remarks')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=> active, 2=> Inactive');
            $table->unsignedBigInteger('assigned_by');
            $table->foreign('assigned_by')->references('id')->on('admins');
            // $table->foreign('assigned_by')->references('id')->on('admins')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_warehouse_mappings');
    }
};
