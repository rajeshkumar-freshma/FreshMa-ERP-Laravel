<?php

namespace Database\Seeders;

use App\Models\IncomeExpenseTransaction;
use App\Models\IncomeExpenseTransactionDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IncomeExpenseAddSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('income_expense_transactions')->delete();
        DB::table('income_expense_transaction_details')->delete();

        DB::beginTransaction();

        DB::unprepared('SET IDENTITY_INSERT income_expense_transactions ON');

        // Dummy data for IncomeExpenseTransaction
        $incomeExpenseData = [
            'expense_invoice_number' => 'EXP123456',
            'warehouse_id' => null, // Assuming related_to is 1 for stores
            'store_id' => 1, // Replace with an existing store ID
            'income_expense_type_id' => 1, // Replace with an existing type ID
            'transaction_datetime' => Carbon::now()->format('Y-m-d H:i:s'),
            'related_to' => 2, // Assuming 2 means related to warehouse
            'sub_total' => 5000.00,
            'reference_id' => 'REF001',
            'adjustment_amount' => 50.00,
            'total_amount' => 5050.00,
            'status' => 1,
            'is_notification_send_to_user' => 0,
            'remarks' => 'Dummy expense transaction for seeding',
            'payment_status' => 3, // Unpaid
        ];

        $incomeExpenseTransaction = IncomeExpenseTransaction::create($incomeExpenseData);

        // Dummy data for IncomeExpenseTransactionDetail
        $expenseItems = [
            [
                'expense_type_id' => 1, // Replace with an existing expense type ID
                'expense_amount' => 2000.00,
                'remarks' => 'Office supplies',
            ],
            // Add more items as needed
        ];

        foreach ($expenseItems as $item) {
            if (!is_null($item['expense_type_id'])) {
                $transactionDetail = new IncomeExpenseTransactionDetail();
                $transactionDetail->ie_transaction_id = $incomeExpenseTransaction->id;
                $transactionDetail->ie_type_id = $item['expense_type_id'];
                $transactionDetail->others_name = 'No name';
                $transactionDetail->employee_id = ""; // You can specify an employee ID if needed
                $transactionDetail->amount = $item['expense_amount'];
                $transactionDetail->remarks = $item['remarks'];
                $transactionDetail->save();
            } else {
                // Handle the case where expense_type_id is null (optional)
                // Log::error('Expense type ID is null for an item.');
            }
        }

        DB::unprepared('SET IDENTITY_INSERT income_expense_transactions OFF');

        DB::commit();

    }
}
