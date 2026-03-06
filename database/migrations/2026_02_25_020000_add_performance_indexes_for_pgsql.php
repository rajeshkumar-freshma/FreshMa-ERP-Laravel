<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        // Dashboard and reporting
        DB::statement('CREATE INDEX IF NOT EXISTS idx_sales_orders_status_payment_delivered ON sales_orders (status, payment_status, delivered_date)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_sales_orders_store_delivered ON sales_orders (store_id, delivered_date)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_sales_orders_bill_machine ON sales_orders (bill_no, machine_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_purchase_orders_payment_delivery ON purchase_orders (payment_status, delivery_date)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_income_expense_txn_status_type_date ON income_expense_transactions (status, income_expense_type_id, transaction_datetime)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_income_expense_txn_store ON income_expense_transactions (store_id)');

        // SQL sync / conversion paths
        DB::statement('CREATE INDEX IF NOT EXISTS idx_live_sales_bills_datetime ON live_sales_bills ("ItemsaleDateTime")');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_live_sales_bills_bill_machine ON live_sales_bills ("billNo", "MachineName")');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_live_sales_bill_details_live_bill ON live_sales_bill_details (live_sales_bill_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_live_sales_bill_details_bill_machine ON live_sales_bill_details ("billNo", "MachineName")');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_machine_data_slno ON machine_data ("Slno")');

        // Cash and transfer pages
        DB::statement('CREATE INDEX IF NOT EXISTS idx_cash_register_txn_date_store_status ON cash_register_transactions (transaction_datetime, store_id, status)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_cash_register_txn_payment_type ON cash_register_transactions (payment_type_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_product_transfers_from_store ON product_transfers (from_store_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP INDEX IF EXISTS idx_sales_orders_status_payment_delivered');
        DB::statement('DROP INDEX IF EXISTS idx_sales_orders_store_delivered');
        DB::statement('DROP INDEX IF EXISTS idx_sales_orders_bill_machine');
        DB::statement('DROP INDEX IF EXISTS idx_purchase_orders_payment_delivery');
        DB::statement('DROP INDEX IF EXISTS idx_income_expense_txn_status_type_date');
        DB::statement('DROP INDEX IF EXISTS idx_income_expense_txn_store');
        DB::statement('DROP INDEX IF EXISTS idx_live_sales_bills_datetime');
        DB::statement('DROP INDEX IF EXISTS idx_live_sales_bills_bill_machine');
        DB::statement('DROP INDEX IF EXISTS idx_live_sales_bill_details_live_bill');
        DB::statement('DROP INDEX IF EXISTS idx_live_sales_bill_details_bill_machine');
        DB::statement('DROP INDEX IF EXISTS idx_machine_data_slno');
        DB::statement('DROP INDEX IF EXISTS idx_cash_register_txn_date_store_status');
        DB::statement('DROP INDEX IF EXISTS idx_cash_register_txn_payment_type');
        DB::statement('DROP INDEX IF EXISTS idx_product_transfers_from_store');
    }
};
