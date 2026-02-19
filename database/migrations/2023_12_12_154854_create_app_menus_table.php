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
        Schema::create('app_menus', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('app_menus');
    }
};
