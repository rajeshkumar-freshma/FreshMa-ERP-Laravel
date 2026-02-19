<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Core\Traits\SpatieLogsActivity;
use hisorange\BrowserDetect\Facade as Browser;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes, SpatieLogsActivity;
    protected $guarded = [];
    // protected static $logAttributes = ['*'];
    // protected static $recordEvents = ['created', 'updated', 'deleted'];
    // protected static $ignoreChangedAttributes = ['password', 'updated_at'];
    // protected static $logOnlyDirty = true;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     $logOptions = new LogOptions;
    //     $logOptions->logAll();
    //     $logOptions->logOnlyDirty();

    //     return $logOptions;
    // }
    // public function getDescriptionForEvent(string $eventName): string
    // {
    //     return "This model has been {$eventName}";
    // }
    // public function tapActivity(Activity $activity, string $eventName)
    // {
    //     $activity->ip_address = request()->ip();
    //     $activity->url = request()->fullUrl();
    //     $activity->device = request()->header('User-Agent');
    // }
    // // public function tapActivity(Activity $activity, string $eventName)
    // // {
    // //     $activity->description = "activity.logs.message.{$eventName}";
    // // }
}
