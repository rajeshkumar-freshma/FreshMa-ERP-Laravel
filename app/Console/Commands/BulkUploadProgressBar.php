<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use App\Jobs\ImportExcelJob;

class BulkUploadProgressBar extends Command
{
    protected $signature = 'import:excel';

    protected $description = 'Laravel Excel importer';

    public function handle()
    {
        Log::info('Live Conversion Schedule run');
        ImportExcelJob::dispatch();
    }
}
