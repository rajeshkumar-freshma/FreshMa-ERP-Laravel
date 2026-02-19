<?php

namespace App\Http\Controllers\Api\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\UserAdvance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        $user = User::where('id', Auth::user()->id)->first();

        $user_advance = UserAdvance::where('user_id', Auth::user()->id)->orderByDesc('id')->pluck('total_amount')->first();
        $sales_amount = PurchaseOrder::where('supplier_id', Auth::user()->id)->sum('total');
        $sales_pending_amount = PurchaseOrder::where('supplier_id', Auth::user()->id)->whereIn('payment_status', [2,3])->sum('total');
        // $sale_order = PurchaseOrder::where('supplier_id', Auth::user()->id)->select(DB::raw('count(id) as sales_count'), DB::raw("DATE_FORMAT(delivery_date, '%M-%Y') delivery_month"),  DB::raw('YEAR(delivery_date) year, MONTH(delivery_date) month'))
        //     ->groupby('year','month')
        //     ->get();

        return response()->json([
            'status' => 200,
            'user_advance' => $user_advance,
            'sales_amount' => $sales_amount,
            'sales_pending_amount' => $sales_pending_amount,
            'message' => 'Welcome Mr.'. $user->name
        ]);
    }
}
