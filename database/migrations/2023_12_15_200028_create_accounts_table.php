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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_number')->unique(); // Using bigInteger for larger account numbers
            $table->string('account_holder_name');
            $table->string('account_type');
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('bank_ifsc_code'); // Assuming IFSC code is alphanumeric and 11 characters long
            $table->decimal('balance', 15, 2); // Adjusting the precision and scale for the balance
            $table->text('address')->nullable(); // Using text for longer addresses
            $table->text('notes')->nullable(); // Allowing notes to be nullable
            $table->tinyInteger('status'); // Assuming status is a small integer with a default value
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
