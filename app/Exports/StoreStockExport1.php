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
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class StoreStockExport1 implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $date, $store_id;

    public function __construct($date, $store_id)
    {
        $this->date = $date;
        $this->store_id = $store_id;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

    public function headings(): array
    {
        $store_id = $this->store_id;

        if (count($store_id) > 0) {
            $stores = Store::whereIn('id', $store_id)->get();
        } else {
            $store_id = Auth::user()->user_stores();
            $stores = Store::whereIn('id', $store_id)->get();
        }

        $headers = ['Items'];
        foreach ($stores as $keys => $store) {
            $headers[] = $store->store_name;
        }

        return $headers;
    }

    public function collection()
    {
        $date = $this->date;
        $store_id = $this->store_id;

        $products = Product::select('id', 'name', 'sku_code', 'image')->get();
        if (count($store_id) > 0) {
            $stores = Store::whereIn('id', $store_id)->get();
        } else {
            $store_id = Auth::user()->user_stores();
            $stores = Store::whereIn('id', $store_id)->get();
        }

        $stock_datas = [];
        $headers = ['Items'];
        foreach ($stores as $keys => $store) {
            $headers[] = $store->store_name;
        }
        foreach ($products as $key => $product) {
            $stock_datas[$key]['product_name'] = $product->name;
            $stock_datas[$key]['sku_code'] = $product->sku_code;
            foreach ($stores as $keys => $store) {
                $value = Product::productstockdetails($product->id, $store->id, $date, $store->store_name);
                $stock_datas[$key]['stock'][] = $value;
            }
        }

        return collect($stock_datas);
    }

    public function map($data): array
    {
        $rows = [];
        foreach ($data['stock'] as $index => $stock) {
            $rows[] = [
                'Product Name' => ($index === 0) ? $data['product_name'] : '',
                'Opening Stock' => $stock['openingstock'],
                'Closing Stock' => $stock['closingstock'],
            ];
        }
        dd($rows);
        return $rows;
    }

    // public function prepareRows($rows)
    // {
    //     Log::info($rows);
    //     return $rows->transform(function ($user) {
    //         $user->name .= ' (prepared)';

    //         return $user;
    //     });
    // }

    // public function view(): View
    // {
    //     ini_set('max_execution_time', '1024');

    //     $date = $this->date;
    //     $store_id = $this->store_id;

    //     $products = Product::select('id', 'name', 'sku_code', 'image')->get();
    //     if (count($store_id) > 0) {
    //         $stores = Store::whereIn('id', $store_id)->get();
    //     } else {
    //         $store_id = Auth::user()->user_stores();
    //         $stores = Store::whereIn('id', $store_id)->get();
    //     }

    //     $stock_data = [];
    //     $headers = ['Items'];
    //     foreach ($stores as $keys => $store) {
    //         $headers[] = $store->store_name;
    //     }
    //     foreach ($products as $key => $product) {
    //         $stock_datas[$key]['product_name'] = $product->name;
    //         foreach ($stores as $keys => $store) {
    //             $value = Product::productstockdetails($product->id, $store->id, $date, $store->store_name);
    //             // $explodeValue = explode(",", $value);
    //             $stock_datas[$key]['stock'][] = $value;
    //             // $stock_datas[$key][] = "Opening Stock : " . $explodeValue[0] . "; Closing Stock : " . $explodeValue[1];
    //         }
    //     }

    //     return view('pages.report.product_stock_report_table', compact('headers', 'stock_datas'));
    // }
}
