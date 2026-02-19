<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InvoiceQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $email;
    public $booking;
    public $subject;
    public $content;
    public $table;
    public $body;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $content, $booking, $table, $body)
    {
        $this->email = $email;
        $this->booking = $booking;
        $this->subject = $subject;
        $this->content = $content;
        $this->table = $table;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info("Job execution started.");
        Mail::to($this->email)->send(new InvoiceMail($this->email, $this->subject, $this->content, $this->booking, $this->table, $this->body));
        Log::info("Job execution completed.");
    }
}
