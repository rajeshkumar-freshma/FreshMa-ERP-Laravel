<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Store;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StoreStockExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $from_date, $to_date, $store_id;

    public function __construct($from_date, $to_date, $store_id)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->store_id = $store_id;
    }

    public function view(): View
    {
        ini_set('max_execution_time', '1024');

        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $store_id = $this->store_id;

        $products = Product::select('id', 'name', 'sku_code', 'unit_id', 'image')->with('unit')->get();
        if (count($store_id) > 0) {
            $stores = Store::whereIn('id', $store_id)->get();
        } else {
            $store_id = Auth::user()->user_stores();
            $stores = Store::whereIn('id', $store_id)->get();
        }

        $stock_data = [];
        $headers = ['Items'];
        foreach ($stores as $keys => $store) {
            $headers[] = $store->store_name;
        }
        foreach ($products as $key => $product) {
            $stock_datas[$key]['product_name'] = $product->name;
            $stock_datas[$key]['sku_code'] = $product->sku_code;
            foreach ($stores as $keys => $store) {
                $value = Product::productstockdetails($product->id, $store->id, $from_date, $to_date, $store->store_name, @$product->unit->unit_short_code);
                // $explodeValue = explode(",", $value);
                $stock_datas[$key]['stock'][] = $value;
                // $stock_datas[$key][] = "Opening Stock : " . $explodeValue[0] . "; Closing Stock : " . $explodeValue[1];
            }
        }
        return view('pages.report.product_stock_report_table', ['headers' => $headers, 'stock_datas' => $stock_datas]);
    }
}
