<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class S3FileDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:s3files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $s3Files = Storage::disk('s3')->allFiles();

        Log::info(count($s3Files));
        foreach ($s3Files as $file) {
            Log::info($file);

            // copy
            Storage::disk('s3backup')->writeStream($file, Storage::disk('s3')->readStream($file));

            // move
            // Storage::disk('local')->writeStream($file, Storage::disk('s3')->readStream($file));
            // Storage::disk('s3')->delete($file);
        }
    }
}
