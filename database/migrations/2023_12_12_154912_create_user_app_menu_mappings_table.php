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
        Schema::create('user_app_menu_mappings', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id'); // in here supplier id also stored  verified in  admin_type like admin_type 2 is supplier id 
            $table->integer('admin_type')->comment('1=>admin, 2=>supplier');
            $table->integer('menu_type')->comment('1=>Bottom Menu, 2=>SideBar Menu');
            $table->longText('app_menu_json');
            $table->longText('remarks')->nullable();
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_app_menu_mappings');
    }
};
