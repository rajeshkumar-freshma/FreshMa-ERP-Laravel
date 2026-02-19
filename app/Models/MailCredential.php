<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailCredential extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mailSetting()
    {
        return $this->hasMany(MailSetting::class, 'id', 'value');
    }
}
