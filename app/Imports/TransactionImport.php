<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\withHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TransactionImport implements ToModel, withHeadingRow, WithChunkReading
{
    private $bankId;

    public function __construct($bankId)
    {
        $this->bankId = $bankId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        // Log the entire row for debugging purposes
        Log::info('Imported Row Data:', $row);

        // Validate header row if needed (assuming header row check is necessary)
        if (!isset($row[0]) || $row[0] != 's.no' ||
            !isset($row[1]) || $row[1] != 'date' ||
            !isset($row[2]) || $row[2] != 'particulars' ||
            !isset($row[3]) || $row[3] != 'debit' ||
            !isset($row[4]) || $row[4] != 'credit' ||
            !isset($row[5]) || $row[5] != 'balance') {
            // Skip processing if header row doesn't match
            Log::warning('Skipping row due to invalid header:', $row);
            return null;
        }

        // Log specific fields of interest after validation
        Log::info('Imported Row Data - Date: ' . $row['date']);
        Log::info('Imported Row Data - Particulars: ' . $row['particulars']);
        Log::info('Imported Row Data - Debit: ' . $row['debit']);
        Log::info('Imported Row Data - Credit: ' . ($row['credit'] > 0 ? $row['debit'] : $row['credit']));
        Log::info('Imported Row Data - Balance: ' . ($row['balance'] !== null && $row['balance'] !== 0 ? 0 : 1));

        // Assuming 'date' is in the correct format, convert it if necessary
        // $transactionDate = Carbon::createFromFormat('d-m-Y', $row['date'])->format('Y-m-d');

        // Create a new Transaction model instance
        $transaction = new Transaction([
            'transaction_account' => $this->bankId,
            'transaction_date' => $row['date'],
            'notes' => $row['particulars'] ?? '', // Ensure to handle null values gracefully
            'available_balance' => $row['balance'] ?? 0, // Assuming 'balance' field is the available balance
            'transaction_amount' => $row['debit'] > 0 ? $row['debit'] : $row['credit'],
            'transaction_type' => $row['credit'] !== null && $row['credit'] !== 0 ? 0 : 1,
        ]);

        // Log completion of row processing
        Log::info('Imported Row Data finished processing:', $row);

        return $transaction;
    }

    public function chunkSize(): int
    {
        return 5000; // Adjust the chunk size as needed based on your import file size
    }

    // /**
    //  * Validate headers of the file
    //  */
    // public function headingRow(): int
    // {
    //     return 1; // Assuming the headers are in the first row
    // }

    // /**
    //  * Validate the headers
    //  */
    // public function validateHeading(array $heading): bool
    // {
    //     $expectedHeaders = ['S.No', 'Date', 'Particulars', 'Debit', 'Credit', 'Balance'];

    //     // Compare the expected headers with the actual headers
    //     foreach ($expectedHeaders as $index => $header) {
    //         if (!isset($heading[$index]) || $heading[$index] !== $header) {
    //             return false; // Headers are incorrect
    //         }
    //     }

    //     return true; // Headers are correct
    // }
}
