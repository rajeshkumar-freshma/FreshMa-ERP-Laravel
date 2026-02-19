<?php

namespace App\Models;
use App\Core\CommonComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CashPaidToOffice extends Model
{
    protected $appends = ['image_full_url', 'status_text', 'status_color_code', 'signature_full_url'];

    protected $guarded = [];

    use HasFactory, SoftDeletes, SpatieLogsActivity;

    /* Default data store Container */
    protected static function booted()
    {
        static::creating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->created_by = Auth::user()->id;
                $data->updated_by = Auth::user()->id;
            } else {
                $data->created_by = 1;
                $data->updated_by = 1;
            }
        });

        static::updating(function ($data) {
            if (Auth::guard('admin')->check()) {
                $data->updated_by = Auth::user()->id;
            } else if (Auth::guard('api')->check()) {
                $data->updated_by = Auth::user()->id;
            } else {
                $data->updated_by = 1;
            }
        });
    }

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function payer_details()
    {
        return $this->hasOne(Admin::class, 'id', 'payer_id')->select('id', 'first_name', 'last_name', 'phone_number', 'user_type');
    }

    public function receiver_details()
    {
        return $this->hasOne(Admin::class, 'id', 'receiver_id')->select('id', 'first_name', 'last_name', 'phone_number', 'user_type');
    }

    // get attributes customized
    public function getImageFullUrlAttribute()
    {
        return commoncomponent::getImageFullUrlPath($this->file, $this->file_path);
    }

    public function getSignatureFullUrlAttribute()
    {
        return commoncomponent::getImageFullUrlPath($this->signature, $this->signature_path);
    }

    public function getStatusTextAttribute()
    {
        $data = config('app.statusinactive');
        // $data = commoncomponent()->arraypositionconversion(config('app.statusinactive'));
        Log::info($data[$this->status]);
        return $data[$this->status]['name'];
    }

    public function getStatusColorCodeAttribute()
    {
        $data = config('app.statusinactive');
        // $data = commoncomponent()->arraypositionconversion(config('app.statusinactive'));
        return $data[$this->status]['color_code'];
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function denomination_date_amounts()
    {
        return $this->hasMany(DenominationDateAmount::class, 'cash_paid_id', 'id');
    }

    public function denominations()
    {
        return $this->hasMany(Denomination::class, 'cash_paid_id', 'id');
    }
}
