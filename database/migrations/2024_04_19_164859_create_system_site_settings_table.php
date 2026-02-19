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
        Schema::create('system_site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->integer('language');
            $table->integer('currency');
            $table->integer('accounting_method');
            $table->string('email');
            $table->integer('customer_group');
            $table->integer('price_group');
            $table->integer('mmode');
            $table->integer('theme');
            // $table->integer('rtl');
            $table->integer('captcha');
            $table->integer('disable_editing');
            $table->integer('rows_per_page');
            $table->integer('dateformat');
            $table->integer('timezone');
            // $table->integer('restrict_calendar');
            $table->integer('warehouse');
            $table->string('image')->nullable();
            $table->string('image_path')->nullable();
            // $table->integer('biller');
            $table->integer('pdf_lib');
            $table->integer('apis');
            $table->integer('use_code_for_slug');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_site_settings');
    }
};
