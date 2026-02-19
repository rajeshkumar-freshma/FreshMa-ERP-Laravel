<?php

namespace App\Jobs;

use App\Imports\TransactionImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelJob implements ShouldQueue, WithProgressBar
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $bankId;

    /**
     * Create a new job instance.
     *
     * @param string $file
     * @param int $bankId
     */
    public function __construct($file, $bankId)
    {
        $this->file = $file;
        $this->bankId = $bankId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::import(new TransactionImport($this->bankId), $this->file);
    }
}
