<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use App\Models\Supplier;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('purchase_orders')->delete();
        // Dummy data for PurchaseOrder
        $purchaseOrderData = [
            'purchase_order_number' => 'PUR-240001',
            'warehouse_id' => 1, // Replace with an existing warehouse ID
            'supplier_id' => 3,  // Replace with an existing supplier ID
            'delivery_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'status' => 1, // Assuming 1 is 'Pending' or 'Approved'
            'is_inc_exp_billable_for_all' => 0,
            'total_request_quantity' => 150,
            'total_tax' => 500.00,
            'discount_type' => 1, // or 'amount'
            'discount_percentage' => 10.00,
            'discount_amount' => 1500.00,
            'sub_total' => 15000.00,
            'adjustment_amount' => 200.00,
            'total' => 13000.00,
            'total_expense_amount' => 300.00,
            'total_expense_billable_amount' => 150.00,
            'remarks' => 'Dummy purchase order for seeding',
            'file' => 'https://example.com/file.jpg', // Example file URL
            'file_path' => 'path/to/file.jpg', // Example file path
        ];

        $purchaseOrder = PurchaseOrder::create($purchaseOrderData);

    }
}
