<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Str;
Use \Carbon\Carbon;
use App\Models\State;
use App\Models\City;
use App\Models\Product;
use App\Models\Unit;
use App\Models\TaxRate;

class HomeController extends Controller
{
    public function home()
    {
        // Get view file location from menu config
        $view = theme()->getOption('page', 'view');
        
        // Check if the page view file exist
        if (view()->exists('pages.'.$view)) {
            return view('pages.'.$view);
        }

        abort(404, 'We can\'t find that page.');
    }

    public function getstate(Request $request)
    {
        $states = State::where("country_id", $request->country_id)->where('status', 1)
            ->get(["name", "id"]);
        return response()->json(['status' => 200, 'states' => $states]);
    }

    public function getcity(Request $request)
    {
        $cities = City::where("state_id", $request->state_id)->where('status', 1)
            ->get(["name", "id"]);
        return response()->json(['status' => 200, 'cities' => $cities]);
    }
    
    public function autocomplete(Request $request)
    {
        $products = Product::where("name", 'LIKE', '%'.$request->name.'%')->where('status', 1)
            ->get(["name", "sku_code", "id"]);
        
        $i=0;
        foreach ($products as $res) {
            $data['label'] = $res->sku_code ==NULL ? $res->name : $res->name . '-' . $res->sku_code;
            $data['value'] = $res->id;
            $getdata[$i++] = $data;
        }

        return response()->json(['status' => 200, 'data' => $getdata]);
    }
    
    public function getproductdetails(Request $request)
    {
        $data['product'] = Product::findOrfail($request->id);
        $data['units'] = Unit::active()->get();
        $data['tax_rates'] = TaxRate::active()->get();
        $data['count'] = $request->count;
        $data['amountdisplay'] = $request->amountdisplay;
        $data['subtotaldisplay'] = $request->subtotaldisplay;
        $data['unit_id'] = $request->unit_id;
        $data['quantity'] = $request->quantity;
        return view('pages.partials.product_search.itemrender', $data)->render();
    }
}
