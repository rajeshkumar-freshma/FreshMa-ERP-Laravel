<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Support\Facades\Auth;
use App\Core\CommonComponent;

class IncomeExpenseTransaction extends Model
{
    protected $appends = ['status_text', 'status_color_code', 'payment_status_text', 'payment_status_color_code'];

    use HasFactory, SoftDeletes, SpatieLogsActivity;

    protected $guarded = [];

    protected static function booted()
    {
        if (Auth::guard('admin')->check() || Auth::guard('api')->check()) {
            static::creating(function ($data) {
                if (Auth::guard('admin')->check()) {
                    $data->user_type = 1;
                    $data->actioned_by = Auth::user()->id;
                } else if (Auth::guard('api')->check()) {
                    $data->user_type = 1;
                    $data->actioned_by = Auth::user()->id;
                } else {
                    $data->user_type = 2;
                    $data->actioned_by = 1;
                }
            });
        } else if (Auth::guard('web')->check()) {
            static::creating(function ($data) {
                $data->user_type = 2;
                $data->actioned_by = Auth::user()->id;
            });
        }
    }

    /* Relationship Container */
    public function created_by_details()
    {
        return $this->hasOne(Admin::class, 'id', 'actioned_by')->withTrashed();
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id')->withTrashed();
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id')->withTrashed();
    }

    public function income_expense_details()
    {
        return $this->hasMany(IncomeExpenseTransactionDetail::class, 'ie_transaction_id', 'id');
    }

    public function expense_documents()
    {
        return $this->hasMany(IncomeExpenseDocument::class, 'reference_id', 'id')->where([['type', 2]]);
    }

    public function payment_transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')->where([['transaction_type', 5]])->with('payment_type_details');
    }

    // public function incomeExpensePaymentTransaction()
    // {
    //     return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id');
    // }
    public function incomeExpensePaymentTransaction()
    {
        return $this->hasMany(PaymentTransaction::class, 'reference_id', 'id')
            ->where(function ($query) {
                $query->where('transaction_type', 8)
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('transaction_type', 5);
                    });
            });
    }

    public function income_expense_types()
    {
        return $this->belongsTo(IncomeExpenseType::class, 'income_expense_type_id', 'id');
    }

    public function user_types()
    {
        return $this->belongsTo(UserType::class);
    }

    public function getStatusTextAttribute()
    {
        $data = config('app.statusinactive');
        // $data = commoncomponent()->arraypositionconversion(config('app.statusinactive'));
        return $data[$this->status]['name'];
    }

    public function getStatusColorCodeAttribute()
    {
        $data = config('app.statusinactive');
        // $data = commoncomponent()->arraypositionconversion(config('app.statusinactive'));
        return $data[$this->status]['color_code'];
    }

    public function getPaymentStatusTextAttribute()
    {
        if ((!empty($this->payment_status) || $this->payment_status != "")) {
            $data = CommonComponent::arraypositionconversion(config('app.payment_status'));
            return (!empty($data[$this->payment_status]) || $data[$this->payment_status] != "") ? $data[$this->payment_status]['name'] : $data[1]['name'];
        } else {
            return config('app.payment_status')[0]['name'];
        }
    }

    public function getPaymentStatusColorCodeAttribute()
    {
        if ((!empty($this->payment_status) || $this->payment_status != "")) {
            $data = CommonComponent::arraypositionconversion(config('app.payment_status'));
            return (!empty($data[$this->payment_status]) || $data[$this->payment_status] != "") ? $data[$this->payment_status]['color_code'] : $data[1]['color_code'];
        } else {
            return config('app.payment_status')[0]['color_code'];
        }
    }
    public function income_expense_type()
    {
        return $this->hasOne(IncomeExpenseType::class, 'id', 'income_expense_type_id');
    }
}
