<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\CommonComponent;
use App\Core\Traits\SpatieLogsActivity;
use App\Tra;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model
{
    use HasFactory,SpatieLogsActivity;

    protected $guarded = [];

    protected $appends = ['image_full_url', 'pan_image_full_url', 'aadhar_image_full_url', 'esi_image_full_url', 'pf_image_full_url', 'bank_passbook_image_full_url'];

    /**
     * Prepare proper error handling for url attribute
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        // if file avatar exist in storage folder
        $avatar = public_path(Storage::url($this->avatar));
        if (is_file($avatar) && file_exists($avatar)) {
            // get avatar url from storage
            return Storage::url($this->avatar);
        }

        // check if the avatar is an external url, eg. image from google
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        // no avatar, return blank avatar
        return asset(theme()->getMediaUrlPath() . 'avatars/blank.png');
    }

    /**
     * User info relation to user model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    /**
     * Unserialize values by default
     *
     * @param $value
     *
     * @return mixed|null
     */
    public function getCommunicationAttribute($value)
    {
        // test to un-serialize value and return as array
        $data = @unserialize($value);
        if ($data !== false) {
            return $data;
        } else {
            return null;
        }
    }

    public function getImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->image, $this->image_path);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    // get attributes customized
    public function getPanImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->pan_file, $this->pan_file_path);
    }
    public function getAadharImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->aadhar_file, $this->aadhar_file_path);
    }
    public function getEsiImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->esi_file, $this->esi_file_path);
    }
    public function getPfImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->pf_file, $this->pf_file_path);
    }
    public function getBankPassbookImageFullUrlAttribute()
    {
        return CommonComponent::getImageFullUrlPath($this->bank_passbook_file, $this->bank_passbook_file_path);
    }
}
