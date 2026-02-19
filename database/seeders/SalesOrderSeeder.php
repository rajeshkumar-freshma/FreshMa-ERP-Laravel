<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\SalesOrder;
use App\Models\Product;
use App\Models\WarehouseStockUpdate;
use App\Models\WarehouseInventoryDetail;
use App\Models\StoreStockUpdate;
use App\Models\StoreInventoryDetail;
use App\Models\SalesOrderDetail;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('sales_orders')->delete();
        // Dummy data for SalesOrder
        $salesOrderData = [
            'sales_from' => 2,
            'sales_type' => 2,
            'warehouse_id' => 1, // Replace with an existing warehouse ID
            'store_id' => 1,     // Replace with an existing store ID
            'vendor_id' => 4,    // Replace with an existing vendor ID
            'invoice_number' => 'INV123456',
            'delivered_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'status' => 10, // Assuming 10 means completed or processed
            'total_request_quantity' => 100,
            'total_given_quantity' => 100,
            'sub_total' => 5000.00,
            'total_expense_amount' => 200.00,
            'total_commission_amount' => 100.00,
            'total_amount' => 5300.00,
            'remarks' => 'Dummy sales order for seeding',
            'is_inc_exp_billable_for_all' => 0,
            'discount_type' => 1,
            'discount_percentage' => 5.00,
            'discount_amount' => 250.00,
            'adjustment_amount' => 50.00,
            'file' => 'https://example.com/file.jpg', // Example file URL
            'file_path' => 'path/to/file.jpg', // Example file path
        ];

        $salesOrder = SalesOrder::create($salesOrderData);

    }
}
