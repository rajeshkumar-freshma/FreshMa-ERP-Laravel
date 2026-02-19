<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // use Batchable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public $notification, $firebasetoken;
    // public function __construct($notification)
    // {
    //     $this->notification = $notification;
    // }
    public function __construct()
    {
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Push notification job Run");
        $firebaseTokens = Admin::where('status', 1)
            ->where('is_deleted', 0)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        $title = "Freshma ERP";
        $content = "Your notification content";
        $image = "URL of notification image";

        Helper::sendPushNotification($firebaseTokens, $title, $content);
    }
}
