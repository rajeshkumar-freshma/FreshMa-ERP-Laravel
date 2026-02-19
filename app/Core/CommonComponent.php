<?php

namespace App\Core;

use App\Models\Adjustment;
use App\Models\Admin;
use App\Models\FishCuttingProductMap;
use App\Models\IncomeExpenseTransaction;
use App\Models\Loan;
use App\Models\MisMatchingAdjustment;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionDocument;
use App\Models\ProductBulkTransfer;
use App\Models\ProductTransfer;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderReturn;
use App\Models\SalesOrder;
use App\Models\SalesOrderReturn;
use App\Models\Spoilage;
use App\Models\Store;
use App\Models\StoreIndentRequest;
use App\Models\StoreInventoryDetail;
use App\Models\StoreSale;
use App\Models\StoreStockUpdate;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\VendorIndentRequest;
use App\Models\Warehouse;
use App\Models\WarehouseIndentRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommonComponent
{
    public static function getCreatedAtFormat($createdAt)
    {
        if ($createdAt != null) {
            $created_at = Carbon::parse($createdAt)->format(config('app.created_at_dateformat'));
            return $created_at;
            // return $createdAt->format(config('app.created_at_dateformat'));
        } else {
            return $createdAt;
        }
    }

    public static function getDateFormat($date)
    {
        if ($date != null) {
            $date = Carbon::parse($date)->format(config('app.actual_dateformat'));
            return $date;
        } else {
            return $date;
        }
    }

    public static function getChangedDateFormat($date)
    {
        if ($date != null) {
            // $date =  Carbon::parse($date)->format('d-m-Y');
            // $date =  Carbon::createFromFormat('M d Y h:i:s A', $date)->format('d-m-Y');
            $data = Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
            Log::info("after update date");
            Log::info($date);
            return $date;
        } else {
            return $date;
        }
    }

    public static function getDateChangedFormat($date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('M d Y h:i:s A', $date)->format(config('app.actual_dateformat'));
        } else {
            return $date;
        }
    }

    public static function slugCreate($name, $slug)
    {
        if ($slug != null) {
            return Str::slug($slug, '-');
        } else {
            return Str::slug($name, '-');
        }
    }

    public static function s3BucketFileUpload($file, $path)
    {
        Log::info("file upload");
        Log::info($file);
        if (ENV('STORAGE_DISK') == 's3') {
            $originalFileName = $file->getClientOriginalName();
            $fileName = date('YmdHis') . '_' . strtolower(str_replace(' ', '_', $originalFileName));
            $filePath = 'media/' . $path . '/' . date('Y') . '/' . date('m');
            $imageURL = $file->storeAs($filePath, $fileName, 's3');
        } elseif (ENV('STORAGE_DISK') == 'local') {
            $originalFileName = $file->getClientOriginalName();
            Log::info("originalFileName ");
            Log::info($originalFileName);
            $fileName = date('YmdHis') . '_' . strtolower(str_replace(' ', '_', $originalFileName));
            $filePath = 's3-backup/media' . '/' . $originalFileName . '/' . date('Y') . '/' . date('m') . '/' . date('d');
            $file->move($filePath, $originalFileName);
            $imageURL = $filePath . '/' . $originalFileName;
        } else {
            $media_path = public_path('media/' . $path);
            $year_folder = $media_path . '/' . date('Y');
            $month_folder = $year_folder . '/' . date('m');

            !file_exists($media_path) && mkdir($media_path, 0777, true);
            !file_exists($year_folder) && mkdir($year_folder, 0777, true);
            !file_exists($month_folder) && mkdir($month_folder, 0777, true);

            $originalFileName = $file->getClientOriginalName();
            Log::info("originalFileName");
            Log::info($originalFileName);
            $fileName = date('YmdHis') . '_' . strtolower(str_replace(' ', '_', $originalFileName));
            $filePath = 'media/' . $path . '/' . date('Y') . '/' . date('m');
            Log::info("filePath");
            Log::info($filePath);
            $imageURL = $file->move($filePath, $fileName);
        }

        return $data = [
            'filePath' => $filePath,
            'fileName' => $fileName,
            'imageURL' => $imageURL,
        ];
    }

    public static function getImageFullUrlPath($file, $path)
    {
        if (is_null($file)) {
            return null;
        } else {
            if (ENV('STORAGE_DISK') == 's3') {
                Log::info($path . '/' . $file);
                if (Storage::disk('s3')->exists($path . '/' . $file)) {
                    return Storage::disk('s3')->temporaryUrl($file, '+10080 minutes');
                } elseif (file_exists(public_path($file))) {
                    return asset($file);
                } else {
                    return null;
                }
            } elseif (ENV('STORAGE_DISK') == 'local') {
                if (file_exists(public_path('s3-backup/' . $file))) {
                    return asset('s3-backup/' . $file);
                } elseif (file_exists(public_path($file))) {
                    return asset($file);
                } else {
                    return null;
                }
            } else {
                if (file_exists(public_path($path . '/' . $file))) {
                    Log::info($path . '/' . $file);
                    return asset($file);
                } else {
                    return null;
                }
            }
        }
    }

    public static function s3BucketFileDelete($file, $path)
    {
        if ($file == null) {
            return true; // If $file is null, no need to delete anything, so return true
        }

        if (ENV('STORAGE_DISK') == 's3') {
            if (Storage::disk('s3')->exists($file)) {
                Storage::disk('s3')->delete($file);
                return true;
            } else {
                return false; // File doesn't exist on S3, so return false
            }
        } elseif (ENV('STORAGE_DISK') == 'local') {
            if (file_exists(public_path($file))) {
                unlink(public_path($file));
                return true;
            } else {
                return false; // File doesn't exist locally, so return false
            }
        } else {
            // For other environments, assuming it's stored in public path
            if (file_exists(public_path($path . '/' . $file))) {
                unlink(public_path($path . '/' . $file));
                return true;
            } else {
                return false; // File doesn't exist, so return false
            }
        }
    }

    public static function attachment_view($url)
    {
        if ($url != null) {
            return '<span><a href=' . $url . ' target="_blank"><i class="fas fa-eye" style="color: green"></i></a></span>';
        } else {
            return '';
        }
    }

    public static function invoice_no($invoice_for, $prefix = null)
    {
        $string = [];
        $number_starts_from = 0;
        $include_year = true;
        $addPrefix = $invoice_for . '_prefix';
        Log::info("addPrefix");
        Log::info($addPrefix);
        $storedPreFix = '';
        if (isset($invoice_for) && $invoice_for !== null) {
            $storedPreFix = SystemSetting::where('key', $invoice_for . '_prefix')->first();
            Log::info("storedPreFix");
            Log::info($storedPreFix);
        }
        if ($storedPreFix !== null) {
            if ($storedPreFix->key == 'purchase_order_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = PurchaseOrder::where('purchase_order_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->purchase_order_number);
                    }
                }
            } elseif ($storedPreFix->key == 'purchase_return_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = PurchaseOrderReturn::where('purchase_order_return_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->purchase_order_return_number);
                    }
                }
            } elseif ($storedPreFix->key == 'store_indent_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = StoreIndentRequest::where('request_code', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->request_code);
                    }
                }
            } elseif ($storedPreFix->key == 'vendor_indent_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = VendorIndentRequest::where('request_code', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->request_code);
                    }
                }
            } elseif ($storedPreFix->key == 'warehouse_indent_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = WarehouseIndentRequest::where('request_code', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->request_code);
                    }
                }
            } elseif ($storedPreFix->key == 'store_sale_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = StoreSale::where('store_sales_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->store_sales_number);
                    }
                }
            } elseif ($storedPreFix->key == 'store_expense_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = IncomeExpenseTransaction::where('expense_invoice_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->expense_invoice_number);
                    }
                }
            } elseif ($storedPreFix->key == 'sales_order_return_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = SalesOrderReturn::where('sales_order_return_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->sales_order_return_number);
                    }
                }
            } elseif ($storedPreFix->key == 'redistribution_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                Log::info('prefix');
                Log::info($prefix);
                if (!empty($prefix)) {
                    $order = ProductTransfer::where('transfer_order_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    Log::info("message");
                    Log::info($order);
                    if ($order != null) {
                        $string = explode($prefix, $order->transfer_order_number);
                    }
                }
                Log::info("string");
                Log::info($string);
            } elseif ($storedPreFix->key == 'spoilage_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = Spoilage::where('spoilage_order_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->spoilage_order_number);
                    }
                }
            } elseif ($storedPreFix->key == 'adjustment_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = Adjustment::where('adjustment_track_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->adjustment_track_number);
                    }
                }
            } elseif ($storedPreFix->key == 'mis_matching_adjustment_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = MisMatchingAdjustment::where('tracking_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->tracking_number);
                    }
                }
            } elseif ($storedPreFix->key == 'product_bulk_transfer_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = ProductBulkTransfer::where('transfer_order_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->transfer_order_number);
                    }
                }
            } elseif ($storedPreFix->key == 'payment_transaction_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = PaymentTransaction::where('transaction_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->transaction_number);
                    }
                }
            } elseif ($storedPreFix->key == 'warehouse_code_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                    Log::info("prefixprefix");
                    Log::info($prefix);
                }
                if (!empty($prefix)) {
                    $order = Warehouse::where('code', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    Log::info("orderorderorder");
                    Log::info($order);
                    if ($order != null) {
                        $string = explode($prefix, $order->code);
                    }
                    Log::info("stringstringstring");
                    Log::info($string);
                }
            } elseif ($storedPreFix->key == 'store_code_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value;
                }
                Log::info("prefixprefix");
                Log::info($prefix);
                if (!empty($prefix)) {
                    $order = Store::where('store_code', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    Log::info("orderorderorder");
                    Log::info($order);
                    if ($order != null) {
                        $string = explode($prefix, $order->store_code);
                    }
                    Log::info("stringstringstring");
                    Log::info($string);
                }
                $include_year = false;
            } elseif ($storedPreFix->key == 'user_code_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-';
                }
                if (!empty($prefix)) {
                    $order = User::where('user_code', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->user_code);
                    }
                }
                $include_year = false;
            } elseif ($storedPreFix->key == 'manager_code_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-';
                }
                if (!empty($prefix)) {
                    $order = Admin::where('user_code', 'like', '%' . $prefix . '%')->where('user_type', 2)->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->user_code);
                    }
                }
                $include_year = false;
            } elseif ($storedPreFix->key == 'partner_code_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-';
                }
                if (!empty($prefix)) {
                    $order = Admin::where('user_code', 'like', '%' . $prefix . '%')->where('user_type', 3)->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->user_code);
                    }
                }
                $include_year = false;
            } elseif ($storedPreFix->key == 'admin_code_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-';
                }
                if (!empty($prefix)) {
                    $order = Admin::where('user_code', 'like', '%' . $prefix . '%')->where('user_type', 1)->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->user_code);
                    }
                }
                $include_year = false;
            } elseif ($storedPreFix->key == 'sale_order_prefix') {
                if ($prefix != null) {
                    $order = SalesOrder::where('invoice_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                } else {
                    $order = SalesOrder::orderBy('id', 'DESC')->first();
                }
                if ($order != null) {
                    $string = explode('-', $order->invoice_number);
                }
            } elseif ($storedPreFix->key == 'income_expense_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = IncomeExpenseTransaction::where('expense_invoice_number', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->expense_invoice_number);
                    }
                }
            } elseif ($storedPreFix->key == 'loan_code_prefix') {
                if ($prefix == null) {
                    $prefix = $storedPreFix->value . '-' . date('y');
                }
                if (!empty($prefix)) {
                    $order = Loan::where('loan_code', 'like', '%' . $prefix . '%')->orderBy('id', 'DESC')->first();
                    if ($order != null) {
                        $string = explode($prefix, $order->loan_code);
                    }
                }
            }
        }
        $get_order_count = 0;
        if (count($string) > 0) {
            $numString = $string[1];
            $get_order_count = (int) $numString; // Convert the string to an integer
        }
        // $get_order_count = 0;
        // if (isset($string[1])) {
        //     $numString = $string[1];
        //     $get_order_count = (int) $numString; // Convert the string to an integer
        // }

        $get_order_count += 1;

        if ($get_order_count > $number_starts_from) {
            $get_order_count = $get_order_count;
        } else {
            $get_order_count = $number_starts_from;
        }
        if (strlen($get_order_count) == 5) {
            $get_order_count = $get_order_count;
        } elseif (strlen($get_order_count) == 4) {
            $get_order_count = '0' . $get_order_count;
        } elseif (strlen($get_order_count) == 3) {
            $get_order_count = '00' . $get_order_count;
        } elseif (strlen($get_order_count) == 2) {
            $get_order_count = '000' . $get_order_count;
        } elseif (strlen($get_order_count) == 1) {
            $get_order_count = '0000' . $get_order_count;
        }
        if ($include_year == true) {
            $invoice_number = $prefix . $get_order_count;
        } else {
            $invoice_number = $prefix . $get_order_count;
        }
        return $invoice_number;
    }
    public static function arraypositionconversion($array)
    {
        array_unshift($array, "");

        unset($array[0]);

        return $array;
    }

    public static function fishcuttingcalculation($expression, $weight, $store_id, $product_id, $sales_order_id)
    {
        DB::beginTransaction();
        // try {
        if ($expression == 'addition') {
            $quantity = +$weight;
        } else if ($expression == 'subtraction') {
            $quantity = -$weight;
        }

        $fishcutting_details = FishCuttingProductMap::where('main_product_id', $product_id)->orderbyDesc('id')->first();
        if ($fishcutting_details == null) {
            if ($quantity != 0) {
                $store_inventory_exists = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->first();
                $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                $store_stock_detail = new StoreStockUpdate();
                $store_stock_detail->from_warehouse_id = 1;
                $store_stock_detail->store_id = $store_id;
                $store_stock_detail->product_id = $product_id;
                $store_stock_detail->reference_id = $sales_order_id;
                $store_stock_detail->reference_table = 10; //10 fish cutting table
                $store_stock_detail->stock_update_on = Carbon::now();
                $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                $store_stock_detail->adding_stock = @$quantity;
                $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                $store_stock_detail->status = 1;
                $store_stock_detail->save();
            }

            $store_stock_detail = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
            $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->first();
            if ($store_inventory == null) {
                $store_inventory = new StoreInventoryDetail();
                $store_inventory->store_id = $store_id;
                $store_inventory->product_id = $product_id;
            }
            $store_inventory->weight = @$store_inventory->weight+@$quantity;
            $store_inventory->status = 1;
            $store_inventory->save();
        }

        $eggs = 0;
        $fishcutting_details = FishCuttingProductMap::where('main_product_id', $product_id)->orderbyDesc('id')->first();
        if ($fishcutting_details != null) {
            $store_inventory_exists = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->first();
            if ($store_inventory_exists != null && $store_inventory_exists->weight > 0) {
                $remaining_weight = $store_inventory_exists->weight + $quantity;
                if ($quantity != 0) {
                    $quantity = $remaining_weight < 0 ? -$store_inventory_exists->weight : $quantity;
                    $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                    $store_stock_detail = new StoreStockUpdate();
                    $store_stock_detail->store_id = $store_id;
                    $store_stock_detail->product_id = $product_id;
                    $store_stock_detail->reference_id = $sales_order_id;
                    $store_stock_detail->reference_table = 3; //3 sales order returns a reference
                    $store_stock_detail->stock_update_on = Carbon::now();
                    $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                    $store_stock_detail->adding_stock = @$quantity;
                    $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                    $store_stock_detail->status = 1;
                    $store_stock_detail->save();
                }

                $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $product_id], ['status', 1]])->first();
                if ($store_inventory == null) {
                    $store_inventory = new StoreInventoryDetail();
                    $store_inventory->store_id = $store_id;
                    $store_inventory->product_id = $product_id;
                }
                $store_inventory->weight = @$store_inventory->weight+@$quantity;
                $store_inventory->status = 1;
                $store_inventory->save();

                if (($remaining_weight) < 0) {
                    $grouped_products = ($fishcutting_details != null && $fishcutting_details->grouped_product != null) ? json_decode($fishcutting_details->grouped_product) : [];
                    $quantity = 0;
                    if (count($grouped_products) > 0) {
                        foreach ($grouped_products as $key => $grouped_product) {
                            $quantity = ($remaining_weight * $grouped_product->percentage) / 100;

                            if ($store_id != null && $store_id != "null") {
                                if ($quantity != 0) {
                                    $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                    $store_stock_detail = new StoreStockUpdate();
                                    $store_stock_detail->store_id = $store_id;
                                    $store_stock_detail->reference_table = 3; //3 sales order returns a reference
                                    $store_stock_detail->reference_id = $sales_order_id;
                                    $store_stock_detail->product_id = $grouped_product->product_id;
                                    $store_stock_detail->stock_update_on = Carbon::now();
                                    $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                    $store_stock_detail->adding_stock = @$quantity;
                                    $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                                    $store_stock_detail->status = 1;
                                    $store_stock_detail->remarks = "This is added/Sub by the egg sales.";
                                    $store_stock_detail->save();
                                }

                                $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->first();
                                if ($store_inventory == null) {
                                    $store_inventory = new StoreInventoryDetail();
                                    $store_inventory->store_id = $store_id;
                                    $store_inventory->product_id = $grouped_product->product_id;
                                }
                                $store_inventory->weight = @$store_inventory->weight+@$quantity;
                                $store_inventory->status = 1;
                                $store_inventory->save();
                            }
                        }
                    }
                }
            } else {
                $grouped_products = ($fishcutting_details != null && $fishcutting_details->grouped_product != null) ? json_decode($fishcutting_details->grouped_product) : [];
                $quantity = 0;
                if (count($grouped_products) > 0) {
                    foreach ($grouped_products as $key => $grouped_product) {
                        $total_quantity = ($weight * $grouped_product->percentage) / 100;
                        if ($expression == 'addition') {
                            $quantity = +$total_quantity;
                        } else if ($expression == 'subtraction') {
                            $quantity = -$total_quantity;
                        }

                        if ($store_id != null && $store_id != "null") {
                            if ($quantity != 0) {
                                $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                $store_stock_detail = new StoreStockUpdate();
                                $store_stock_detail->store_id = $store_id;
                                $store_stock_detail->product_id = $grouped_product->product_id;
                                $store_stock_detail->reference_id = $sales_order_id;
                                $store_stock_detail->reference_table = 3; //3 sales order returns a reference
                                $store_stock_detail->stock_update_on = Carbon::now();
                                $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                $store_stock_detail->adding_stock = @$quantity;
                                $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock+@$quantity : @$quantity;
                                $store_stock_detail->status = 1;
                                $store_stock_detail->remarks = "This is added/Sub by the " . $grouped_product->type . " sales.";
                                $store_stock_detail->save();
                            }

                            $store_inventory = StoreInventoryDetail::where([['store_id', $store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->first();
                            if ($store_inventory == null) {
                                $store_inventory = new StoreInventoryDetail();
                                $store_inventory->store_id = $store_id;
                                $store_inventory->product_id = $grouped_product->product_id;
                            }
                            $store_inventory->weight = @$store_inventory->weight+@$quantity;
                            $store_inventory->status = 1;
                            $store_inventory->save();
                        }
                    }
                }
            }
        }
        DB::commit();
        return true;
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Sales Stored Fail');
        // }
    }
    public static function dateformatwithtime($from_date, $to_date)
    {
        return [
            Carbon::parse($from_date)->format('Y-m-d 00:00:00'),
            Carbon::parse($to_date)->format('Y-m-d 23:59:59'),
        ];
    }

    public static function payment_transaction_documents($documents, $table_id, $payment_transaction_id)
    {
        if (is_array($documents)) {
            foreach ($documents as $key => $value) {
                if ($value) {
                    Log::info("file loop");
                    Log::info($value);
                    $payment_transaction_docs = new PaymentTransactionDocument();
                    $imageData = CommonComponent::s3BucketFileUpload($value, 'payment_transaction_document');
                    $imagePath = $imageData['filePath'];
                    $imageUrl = $imageData['imageURL'];

                    $payment_transaction_docs->reference_id = $payment_transaction_id;
                    $payment_transaction_docs->reference_table = $table_id;
                    $payment_transaction_docs->file = $imageUrl;
                    $payment_transaction_docs->file_path = $imagePath;
                    $payment_transaction_docs->attached_by = Auth::user()->id;
                    $payment_transaction_docs->save();
                }
            }
        } else {
            if ($documents) {
                Log::info("file loop");
                Log::info($documents);
                $payment_transaction_docs = new PaymentTransactionDocument();
                $imageData = CommonComponent::s3BucketFileUpload($documents, 'payment_transaction_document');
                $imagePath = $imageData['filePath'];
                $imageUrl = $imageData['imageURL'];

                $payment_transaction_docs->reference_id = $payment_transaction_id;
                $payment_transaction_docs->reference_table = $table_id;
                $payment_transaction_docs->file = $imageUrl;
                $payment_transaction_docs->file_path = $imagePath;
                $payment_transaction_docs->attached_by = Auth::user()->id;
                $payment_transaction_docs->save();
            }
        }
    }

    public function thousand_separator($value)
    {
        $formatted_number = number_format($value, 2, '.', ',');
        $formatted_number = str_replace(',', '', $formatted_number); // Remove the comma added by number_format
        return $formatted_number = number_format($formatted_number, 2, '.', ','); // Format without comma, then add the comma after every two digits

        return number_format($value, 2, '.', ',');
    }
}
