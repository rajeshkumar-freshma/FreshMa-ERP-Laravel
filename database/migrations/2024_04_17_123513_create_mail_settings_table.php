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
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('mailer_type'); // Assuming this is for email addresses, VARCHAR might suffice
            $table->string('name'); // VARCHAR for the name associated with the settings
            $table->string('email'); // Assuming this is for email addresses, VARCHAR might suffice
            $table->string('smtp_host'); // VARCHAR for the hostname
            $table->string('smtp_user_name'); // VARCHAR for the username
            $table->string('smtp_password'); // VARCHAR for the password
            $table->unsignedSmallInteger('smtp_port'); // Small Integer as ports are typically within a limited range
            $table->integer('smtp_encryption_type')->nullable(); // VARCHAR, unless you're enforcing specific encryption methods
            $table->tinyInteger('status')->default(0); // BOOLEAN (or TINYINT) for status (assuming 0 for inactive, 1 for active)
            $table->timestamps(); // TIMESTAMP for created_at and updated_at
            $table->softDeletes(); // Soft delete column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_settings');
    }
};
