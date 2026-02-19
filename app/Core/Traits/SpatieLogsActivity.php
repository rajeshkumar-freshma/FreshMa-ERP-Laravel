<?php

namespace App\Core\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Facades\Log;

trait SpatieLogsActivity
{
    use LogsActivity;

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['created', 'updated', 'deleted']; // Include 'deleted'
    protected static $ignoreChangedAttributes = ['password', 'updated_at'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions;
        $logOptions->logAll();
        $logOptions->logOnlyDirty();

        return $logOptions;
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    // public function tapActivity(Activity $activity)
    // {
    //     $activity->ip_address = request()->ip();
    //     $activity->url = request()->fullUrl();
    //     $activity->device = request()->header('User-Agent');
    //     // Detect browser details
    //     $browser = new Browser();
    //     $browserDetails = $browser->detect(); // This method returns an array of browser details
    //     Log::info($browserDetails);
    //     // Convert array to JSON
    //     $browserDetailsJSON = json_encode($browserDetails);
    //     // Serialize browser detection properties
    //     $activity->browser_detection_properties = $browserDetailsJSON;
    //     // Serialize browser detection properties
    //     // $activity->browser_detection_properties = serialize(Browser::detect()->toArray());
    // }
    public function tapActivity(Activity $activity)
    {
        $activity->ip_address = request()->ip();
        $activity->url = request()->fullUrl();
        $activity->device = request()->header('User-Agent');

        // Detect browser details
        $browser = new \hisorange\BrowserDetect\Parser();
        $browserDetails = $browser->detect(); // This method returns a hisorange\BrowserDetect\Result object

        // Serialize browser details into a string
        $browserDetailsString = serialize($browserDetails);

        // Store serialized browser details in the database
        // $activity->browser_detection_properties = $browserDetailsString;
    }
}
