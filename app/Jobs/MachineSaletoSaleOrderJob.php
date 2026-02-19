<?php

namespace App\Jobs;

use App\Core\CommonComponent;
use App\Models\FishCuttingDetail;
use App\Models\FishCuttingProductMap;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LiveSalesBill; // Live sale data details from Easse machine Model to store Our Database
use App\Models\LiveSalesBillDetail; // Live sale data details from Easse machine Model to store Our Database
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Product;
use App\Models\MachineData;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Models\StoreInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\User;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\DB;

class MachineSaletoSaleOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Sale Order Job Executing');
        DB::beginTransaction();
        try {
            $user_id = User::first()->id;

            $paymentTypeId = PaymentType::where('slug', 'cash-on-hand')->value('id');
            if (!$paymentTypeId) {
                $paymentTypeId = PaymentType::first()->value('id');
            }
            Log::info("payment Type Id");
            Log::info($paymentTypeId);

            // Fetch Machine Bill Data
            $from_date = date('Y-m-d 00:00:00');
            $to_date = date('Y-m-d 23:59:59');

            $machinelivesalebills = LiveSalesBill::whereBetween('ItemsaleDateTime', [$from_date, $to_date])
                ->orderBy('ItemsaleDateTime', 'ASC')
                ->get();
            Log::info("machine live sales stored in sales orders tables start");
            // Log::info($machinelivesalebills);
            Log::info("count");
            // Log::info($machinelivesalebills->count());
            foreach ($machinelivesalebills as $key => $machinelivesalebill) {
                // Looping Bill Details
                Log::info("machine live sales stored entered");
                // Log::info($machinelivesalebill);
                $sale_order_checks = SalesOrder::where([['bill_no', $machinelivesalebill->billNo], ['machine_id', $machinelivesalebill->MachineName]])->whereBetween('delivered_date', [$from_date, $to_date])->first();
                Log::info("sale_order_checks");
                // Log::info($sale_order_checks);
                $saleBillDetails = LiveSalesBillDetail::where([['live_sales_bill_id', $machinelivesalebill->id], ['MachineName', $machinelivesalebill->MachineName]])->get();
                Log::info("saleBillDetails");
                // Log::info($saleBillDetails);
                $machine_data_check_exists = MachineData::where('Slno', $machinelivesalebill->MachineName)->first();
                Log::info("machine_data_check_exists");
                // Log::info($machine_data_check_exists);
                if (empty($sale_order_checks) && count($saleBillDetails) > 0) {
                    Log::info("saleBillDetails if condtions entered");
                    $total_amount = 0;
                    $invoice_number = CommonComponent::invoice_no('sale_order', 'WLK-');
                    $order_sales = new SalesOrder();
                    $order_sales->sales_from = 1;
                    $order_sales->sales_type = 1;
                    $order_sales->vendor_id = $user_id; // Walk-In
                    $order_sales->invoice_number = $invoice_number;
                    $order_sales->delivered_date = $machinelivesalebill->ItemsaleDateTime;
                    $order_sales->bill_no = $machinelivesalebill->billNo;
                    $order_sales->machine_id = $machinelivesalebill->MachineName;
                    $order_sales->store_id = @$machine_data_check_exists->store_id;
                    $order_sales->warehouse_id = @$machine_data_check_exists->store_details != null ? $machine_data_check_exists->store_details->warehouse_id : null;
                    // $order_sales->delivered_date = Carbon::createFromFormat('d/m/Y H:i:s', $machinelivesalebill->ItemsaleDateTime)->format('Y-m-d H:i:s');;
                    $order_sales->status = 10;
                    $order_sales->total_request_quantity = $saleBillDetails->sum('wtQty');
                    $order_sales->total_given_quantity = $saleBillDetails->sum('wtQty');
                    $order_sales->sub_total = $saleBillDetails->sum('price');
                    $order_sales->total_expense_amount = 0;
                    $order_sales->total_commission_amount = 0;
                    $order_sales->total_amount = $saleBillDetails->sum('price');
                    $order_sales->is_inc_exp_billable_for_all = 0;
                    $order_sales->created_by = 1;
                    $order_sales->updated_by = 1;
                    $order_sales->save();
                    Log::info("sales order data values");
                    Log::info("order_sales->store_id");
                    // Log::info($order_sales->store_id);
                    Log::info("store Id End");
                    // Log::info($order_sales->get());
                    $order_sales_id = $order_sales->id;
                    foreach ($saleBillDetails as $key => $saleBillDetail) {
                        $product_data = Product::where('id', $saleBillDetail->pluNumber)->first();
                        if ($product_data == null) {
                            $product_data = Product::where('id', 1)->first();
                        }
                        // Log::info($product_data);
                        $product_price_amount = $saleBillDetail->wtQty * $saleBillDetail->price;

                        $sales_order_detail = new SalesOrderDetail();
                        $sales_order_detail->sales_order_id = $order_sales->id;
                        $sales_order_detail->product_id = $product_data->id;
                        $sales_order_detail->sku_code = $product_data->sku_code;
                        $sales_order_detail->name = $product_data->name;
                        $sales_order_detail->is_inc_exp_billable = 0;
                        $sales_order_detail->unit_id = $product_data->unit_id;
                        $sales_order_detail->request_quantity = $saleBillDetail->wtQty;
                        $sales_order_detail->given_quantity = $saleBillDetail->wtQty;
                        $sales_order_detail->amount = $product_price_amount;
                        $sales_order_detail->per_unit_price = $saleBillDetail->price;
                        $sales_order_detail->tax_id = null;
                        $sales_order_detail->tax_value = null;
                        $sales_order_detail->discount_type = null;
                        $sales_order_detail->discount_amount = null;
                        $sales_order_detail->discount_percentage = null;
                        $sales_order_detail->sub_total = $product_price_amount;
                        $sales_order_detail->commission_percentage = null;
                        $sales_order_detail->commission_amount = 0;
                        $sales_order_detail->expense_amount = 0;
                        $sales_order_detail->total = $product_price_amount;
                        $sales_order_detail->save();

                        $quantity = -$sales_order_detail->given_quantity;
                        if ($quantity != 0) {
                            $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $order_sales->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                            $store_stock_detail = new StoreStockUpdate();
                            $store_stock_detail->store_id = $order_sales->store_id;
                            $store_stock_detail->product_id = $product_data->id;
                            $store_stock_detail->reference_id = $order_sales->id;
                            $store_stock_detail->reference_table = 14; //14 Live Machine Sales table
                            $store_stock_detail->stock_update_on = Carbon::now();
                            $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                            $store_stock_detail->adding_stock = @$quantity;
                            $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                            $store_stock_detail->status = 1;
                            $store_stock_detail->save();
                        }

                        $store_stock_detail = StoreStockUpdate::where([['store_id', $order_sales->store_id], ['product_id', $product_data->id], ['status', 1]])->orderBy('id', 'DESC')->first();
                        $store_inventory = StoreInventoryDetail::where([['store_id', $order_sales->store_id], ['product_id', $product_data->id], ['status', 1]])->first();
                        if ($store_inventory == null) {
                            $store_inventory = new StoreInventoryDetail();
                            $store_inventory->store_id = $order_sales->store_id;
                            $store_inventory->product_id = $product_data->id;
                        }
                        $store_inventory->weight = @$store_inventory->weight + @$quantity;
                        $store_inventory->status = 1;
                        $store_inventory->save();

                        $fishcutting = FishCuttingProductMap::where('main_product_id', $product_data->id)->orderbyDesc('id')->first();
                        if ($fishcutting != null) {
                            $grouped_products = json_decode($fishcutting->grouped_product);

                            $quantity = 0;
                            foreach ($grouped_products as $key => $grouped_product) {
                                $quantity = - (($quantity * $grouped_product->percentage) / 100);
                                // if ($grouped_product->type == 'slice') {
                                //     $quantity = -$slice;
                                // } else if ($grouped_product->type == 'head') {
                                //     $quantity = -$head;
                                // }
                                if ($order_sales->store_id != null && $order_sales->store_id != "null") {
                                    if ($quantity != 0) {
                                        $store_stock_detail_exists = StoreStockUpdate::where([['store_id', $order_sales->store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->orderBy('id', 'DESC')->first();
                                        $store_stock_detail = new StoreStockUpdate();
                                        $store_stock_detail->store_id = $order_sales->store_id;
                                        $store_stock_detail->product_id = $grouped_product->product_id;
                                        $store_stock_detail->reference_id = $order_sales->id;
                                        $store_stock_detail->reference_table = 14; //14 Live Machine Sales table
                                        $store_stock_detail->stock_update_on = Carbon::now();
                                        $store_stock_detail->existing_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock : 0;
                                        $store_stock_detail->adding_stock = @$quantity;
                                        $store_stock_detail->total_stock = ($store_stock_detail_exists != null && $store_stock_detail_exists->total_stock != null) ? $store_stock_detail_exists->total_stock + @$quantity : @$quantity;
                                        $store_stock_detail->status = 1;
                                        $store_stock_detail->save();
                                    }

                                    $store_inventory = StoreInventoryDetail::where([['store_id', $order_sales->store_id], ['product_id', $grouped_product->product_id], ['status', 1]])->first();
                                    if ($store_inventory == null) {
                                        $store_inventory = new StoreInventoryDetail();
                                        $store_inventory->store_id = $order_sales->store_id;
                                        $store_inventory->product_id = $grouped_product->product_id;
                                    }
                                    $store_inventory->weight = @$store_inventory->weight + @$quantity;
                                    $store_inventory->status = 1;
                                    $store_inventory->save();
                                }
                            }
                        }

                        $total_amount += $product_price_amount;
                    }

                    $round_off_total = round($total_amount);
                    $round_off_amount = round($total_amount) - $total_amount;

                    $order_sales->sub_total = $total_amount;
                    $order_sales->round_off_amount = round($round_off_amount, 2);
                    $order_sales->total_amount = $round_off_total;
                    $order_sales->save();

                    $payment_transaction = new PaymentTransaction();
                    $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
                    $payment_transaction->transaction_type = 2; // Sales Order
                    $payment_transaction->type = 1; // Credit
                    $payment_transaction->payment_type_id = $paymentTypeId; // Cash On Hand
                    $payment_transaction->reference_id = $order_sales_id;
                    $payment_transaction->amount = $order_sales->total_amount;
                    $payment_transaction->transaction_datetime = Carbon::now();
                    $payment_transaction->status = 1; // Active
                    $payment_transaction->save();

                    DB::commit();
                }
            }
            Log::info('Sale Order Job Completed');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
        }
    }
}
