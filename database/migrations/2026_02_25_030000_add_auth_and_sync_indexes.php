<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        // Auth/login lookup paths
        DB::statement('CREATE INDEX IF NOT EXISTS idx_admins_email ON admins (email)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_admins_phone ON admins (phone_number)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_users_type_email ON users (user_type, email)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_users_type_phone ON users (user_type, phone_number)');

        // App menu mapping lookup during mobile login
        DB::statement('CREATE INDEX IF NOT EXISTS idx_user_app_menu_mapping_lookup ON user_app_menu_mappings (admin_id, admin_type, menu_type, status)');

        // Sync watermark lookup
        DB::statement('CREATE INDEX IF NOT EXISTS idx_cron_job_completion_lookup ON cron_job_completion_times (cron_job_name, end_time)');
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP INDEX IF EXISTS idx_admins_email');
        DB::statement('DROP INDEX IF EXISTS idx_admins_phone');
        DB::statement('DROP INDEX IF EXISTS idx_users_type_email');
        DB::statement('DROP INDEX IF EXISTS idx_users_type_phone');
        DB::statement('DROP INDEX IF EXISTS idx_user_app_menu_mapping_lookup');
        DB::statement('DROP INDEX IF EXISTS idx_cron_job_completion_lookup');
    }
};
