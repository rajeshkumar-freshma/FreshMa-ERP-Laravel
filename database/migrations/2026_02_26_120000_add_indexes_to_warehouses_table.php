<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->index('status', 'idx_warehouses_status');
            $table->index('created_at', 'idx_warehouses_created_at');
            $table->index(['status', 'created_at'], 'idx_warehouses_status_created_at');
            $table->index('city_id', 'idx_warehouses_city_id');
            $table->index('state_id', 'idx_warehouses_state_id');
            $table->index('country_id', 'idx_warehouses_country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropIndex('idx_warehouses_status');
            $table->dropIndex('idx_warehouses_created_at');
            $table->dropIndex('idx_warehouses_status_created_at');
            $table->dropIndex('idx_warehouses_city_id');
            $table->dropIndex('idx_warehouses_state_id');
            $table->dropIndex('idx_warehouses_country_id');
        });
    }
};
