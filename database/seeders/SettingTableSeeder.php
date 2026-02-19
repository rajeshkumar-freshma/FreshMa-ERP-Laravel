<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('system_settings')->delete();
        $user = Admin::first(); // You might want to adjust how you fetch the user

        $invoiceFors = [
            'purchase_order_prefix', 'purchase_return_prefix', 'store_indent_prefix', 'vendor_indent_prefix',
            'warehouse_indent_prefix', 'store_sale_prefix', 'store_expense_prefix', 'sales_order_return_prefix',
            'redistribution_prefix', 'spoilage_prefix', 'adjustment_prefix', 'mis_matching_adjustment_prefix',
            'product_bulk_transfer_prefix', 'payment_transaction_prefix', 'warehouse_code_prefix',
            'store_code_prefix', 'user_code_prefix', 'manager_code_prefix', 'partner_code_prefix', 'admin_code_prefix',
            'sale_order_prefix', 'income_expense_prefix', 'loan_code_prefix'
        ];

        $prefixes = [
            'PUR', 'POR', 'SIR', 'VIR', 'WIR', 'SS', 'SEXP', 'SOR',
            'RDS', 'SPG', 'ADJ', 'MMAS', 'PBT', 'PTrans', 'WHC', 'STC', 'UC',
            'MAN', 'PAR', 'ADM', 'VI', 'EXP', 'LN'
        ];

        // Loop through each combination of invoice_for and prefix
        foreach ($invoiceFors as $index => $invoice_for) {
            // Insert the combination into the system_settings table
            DB::table('system_settings')->insert([
                'key' => $invoice_for,
                'value' => $prefixes[$index] ?? '', // Use the corresponding prefix
            ]);
        }
    }
}
