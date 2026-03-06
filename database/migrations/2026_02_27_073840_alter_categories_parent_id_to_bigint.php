<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * parent_id was created as varchar but stores integer references to id (bigint).
     * PostgreSQL requires matching types for comparisons.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE categories ALTER COLUMN parent_id TYPE bigint USING parent_id::bigint');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE categories ALTER COLUMN parent_id TYPE varchar(255)');
    }
};
