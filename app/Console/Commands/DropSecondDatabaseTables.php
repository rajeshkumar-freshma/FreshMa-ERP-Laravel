<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Schema;

class DropSecondDatabaseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:drop-second-database-tables';
    protected $description = 'Drop all tables from the second database';

    public function handle()
    {
        $connection = config('activitylog.database_connection'); // Replace with your second database connection name
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        // $this->info('Operation cancelled. No tables were dropped.'. json_encode($tables) );
        if ($this->confirm("Do you really wish to drop all tables from database '{$connection}'?")) {
            Schema::disableForeignKeyConstraints();

            // Drop the activity_log table
            $this->dropTableIfExists($connection, 'activity_log');

            Schema::enableForeignKeyConstraints();

            $this->info("The 'activity_log' table from the second database has been dropped.");
        } else {
            $this->info('Drop command cancelled.');
        }
        // if ($this->confirm("Do you really want to drop all tables from the $connection database?")) {

        //     // DB::beginTransaction();

        //     // try {

        //         foreach ($tables as $table) {

        //             $this->dropTableIfExists($connection, $table);

        //             // Schema::dropIfExists($table);

        //         }

        //         // DB::commit();

        //         $this->info('All tables dropped successfully.');

        //     // } catch (\Exception $e) {

        //     //     DB::rollBack();

        //     //     $this->error('An error occurred while dropping tables. No changes were made.');

        //     // }

        // } else {

        //     $this->info('Operation cancelled. No tables were dropped.');

        // }
    }
    protected function dropTableIfExists($connection, $tableName)
    {
        if (Schema::connection($connection)->hasTable($tableName)) {
            Schema::connection($connection)->drop($tableName);
        }
    }
}
