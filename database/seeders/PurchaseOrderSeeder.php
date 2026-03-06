<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
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
            DB::table('purchase_orders')->truncate();

            $warehouse = Warehouse::first();
            $supplier  = Supplier::first();

            if (!$warehouse) {
                $this->call(WarehousesTableSeeder::class);
                $warehouse = Warehouse::first();
            }

            if (!$supplier) {
                $this->call(SupplierTableSeeder::class);
                $supplier = Supplier::first();
            }

            if (!$warehouse || !$supplier) {
                $this->command->error('Warehouse or Supplier not found. Please seed them first.');
                return;
            }

            PurchaseOrder::create([
                'purchase_order_number' => 'PUR-240001',
                'warehouse_id' => $warehouse->id,
                'supplier_id' => $supplier->id,
                'delivery_date' => now()->addDays(15),
                'status' => 1,
                'is_inc_exp_billable_for_all' => 0,
                'total_request_quantity' => 150,
                'total_tax' => 500.00,
                'discount_type' => 1,
                'discount_percentage' => 10.00,
                'discount_amount' => 1500.00,
                'sub_total' => 15000.00,
                'adjustment_amount' => 200.00,
                'total' => 13000.00,
                'total_expense_amount' => 300.00,
                'total_expense_billable_amount' => 150.00,
                'remarks' => 'Dummy purchase order for seeding',
                'file' => 'https://example.com/file.jpg',
                'file_path' => 'path/to/file.jpg',
                'created_by' => 1,
                'approved_by' => 1,
                'updated_by' => 1,
            ]);
        }
}
