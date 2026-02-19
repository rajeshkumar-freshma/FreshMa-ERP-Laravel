<?php

namespace App\Http\Controllers\Admin;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Models\CashRegisterTransaction;
use Illuminate\Support\Str;
Use \Carbon\Carbon;
use App\Models\State;
use App\Models\City;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\SalesOrderReturn;
use App\Models\Unit;
use App\Models\TaxRate;
use App\Models\VendorIndentRequest;
use App\Models\StoreIndentRequest;
use App\Models\WarehouseIndentRequest;
use Auth;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public function getusercode(Request $request)
    {
        $usertype = $request->usertype;
        $prefix = $request->prefix;
        $code = CommonComponent::invoice_no($usertype, $prefix);

        return response()->json([
            'status' => 200,
            'data' => $code,
        ]);
    }
}
