<?php

namespace App\Http\Controllers\Api\Store;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Models\CashPaidToOffice;
use App\Models\CashRegisterTransaction;
use App\Models\Denomination;
use App\Models\DenominationDateAmount;
use App\Models\DenominationType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashtoOfficeController extends Controller
{
    public function cashpaidtoofficelist(Request $request)
    {
        try {
            $store_id = $request->store_id;
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            // Fetch the cash paid details with optional date range filtering
            $cash_paid_details = CashPaidToOffice::where(function ($query) use ($from_date, $to_date) {
                if ($from_date != null && $to_date != null) {
                    $dateformatwithtime = CommonComponent::dateformatwithtime($from_date, $to_date);
                    $query->whereBetween('updated_at', $dateformatwithtime);
                }
            })
            ->where([['store_id', $store_id], ['status', 1]])
            ->with('payer_details', 'receiver_details')
            ->orderBy('id', 'DESC')
            ->paginate(20);

            // Fetch the denomination details
            $denomination = Denomination::where('store_id', $store_id)
                ->orderBy('id', 'DESC')
                ->get();

            return response()->json([
                'status' => 200,
                'returnlists' => $cash_paid_details,
                'denomination' => $denomination,
                'message' => 'Cash Paid Details fetched successfully.',
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error($e);

            // Return a proper response in case of failure
            return response()->json([
                'status' => 400,
                'message' => 'Data not found or an error occurred.',
            ]);
        }
    }


    public function cashpaidtoofficedetail(Request $request)
    {
        try {
            $cash_paid_id = $request->cash_paid_id;

            // Fetch the cash paid details with related payer and receiver details
            $cash_paid_details = CashPaidToOffice::with('payer_details', 'receiver_details')->findOrFail($cash_paid_id);

            // Fetch DenominationDateAmount for this specific cash paid item
            $denominationDateAmount = DenominationDateAmount::where('cash_paid_id', $cash_paid_details->id)->get();

            return response()->json([
                'status' => 200,
                'returnlists' => $cash_paid_details,
                'denominationDateAmount' => $denominationDateAmount,
                'message' => 'Cash Paid Details fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function cashpaidtoofficestore(Request $request)
    {
        DB::beginTransaction();
        // try {

            $imagePath = null;
            $imageUrl = null;
            if ($request->hasFile('file')) {
                $imageData = CommonComponent::s3BucketFileUpload($request->file, 'cash_paid_docs');
                $imagePath = $imageData['filePath'];
                // $imageUrl = $imageData['fileName'];
                $imageUrl = $imageData['imageURL'];
            }

            $signaturePath = null;
            $signatureUrl = null;
            // if ($request->hasFile('signature')) {
            //     $signatureData = CommonComponent::s3BucketFileUpload($request->signature, 'signature');
            //     $signaturePath = $signatureData['filePath'];
            //     $signatureUrl = $signatureData['imageURL'];
            // }

            $cash_paid_to_office = new CashPaidToOffice();
            $cash_paid_to_office->store_id = $request->store_id;
            $cash_paid_to_office->amount = (int)$request->amount;
            $cash_paid_to_office->payer_id = $request->payer_id;
            $cash_paid_to_office->receiver_id = $request->receiver_id;
            if ($imageUrl != null) {
                $cash_paid_to_office->file = $imageUrl;
                $cash_paid_to_office->file_path = $imagePath;
            }
            if ($signatureUrl != null) {
                $cash_paid_to_office->signature = $signatureUrl;
                $cash_paid_to_office->signature_path = $signaturePath;
            }
            $cash_paid_to_office->status = 1;
            $cash_paid_to_office->save();

            $totalAmount = (int) $request->total_amount;
            $cash_paid_to_office_id = $cash_paid_to_office->id;
            //to store cash paid to office information in denomination
            foreach(json_decode($request->denomination_details, true) as $key => $denomination_detail){
                $cash_denominations = new Denomination();
                $cash_denominations->store_id = $request->store_id;
                $cash_denominations->cash_paid_id = $cash_paid_to_office_id;
                $cash_denominations->denomination_id = $denomination_detail['id'];
                $cash_denominations->denomination_value = $denomination_detail['count'];
                $cash_denominations->amount = (int)$request->amount;
                $cash_denominations->total_amount = $totalAmount;
                $cash_denominations->save();
            }

            foreach(json_decode($request->denomination_date_amount, true) as $key => $denomination_date_amount) {
                DenominationDateAmount::create([
                    'dates' => $denomination_date_amount['date'],
                    'amount' => $denomination_date_amount['amount'],
                    'cash_paid_id' => $cash_paid_to_office_id,
                ]);
            }

            $cash_register_transaction = new CashRegisterTransaction();
            $cash_register_transaction->store_id = $request->store_id;
            $cash_register_transaction->payment_type_id = 1;
            $cash_register_transaction->amount = (int)$request->amount;
            $cash_register_transaction->transaction_datetime = Carbon::now();
            $cash_register_transaction->type = 1; // Credit
            $cash_register_transaction->note = $request->note;
            $cash_register_transaction->save();

            DB::commit();
            return response()->json([
                'status' => 200,
                'returnlists' => $cash_paid_to_office,
                'denomination' => $cash_paid_to_office->denominations,
                'denomination_date_time' => $cash_paid_to_office->denomination_date_amounts,
                'message' => 'Cash Paid Stored successfully.',
            ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Cash Register Store Failed.',
        //     ]);
        // }
    }

    public function cashpaidtoofficeupdate(Request $request)
    {
        $cash_paid_id = $request->cash_paid_id;
        $cash_paid_to_office = CashPaidToOffice::findOrFail($cash_paid_id);
        if ($request->hasFile('file')) {
            CommonComponent::s3BucketFileDelete($cash_paid_to_office->file, $cash_paid_to_office->file_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'cash_paid_docs');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        if ($request->hasFile('signature')) {
            CommonComponent::s3BucketFileDelete($cash_paid_to_office->signature, $cash_paid_to_office->signature_path);

            $signatureData = CommonComponent::s3BucketFileUpload($request->signature, 'signature');
            $signaturePath = $signatureData['filePath'];
            $signatureUrl = $signatureData['imageURL'];
        }

        $cash_paid_to_office->store_id = $request->store_id;
        $cash_paid_to_office->amount = $request->amount;
        $cash_paid_to_office->payer_id = $request->payer_id;
        $cash_paid_to_office->receiver_id = $request->receiver_id;
        if ($request->hasFile('file')) {
            $cash_paid_to_office->file = $imageUrl;
            $cash_paid_to_office->file_path = $imagePath;
        }
        if ($request->hasFile('signature')) {
            $cash_paid_to_office->signature = $signatureUrl;
            $cash_paid_to_office->signature_path = $signaturePath;
        }
        $cash_paid_to_office->status = $request->status;
        $cash_paid_to_office->save();

        return response()->json([
            'status' => 200,
            'returnlists' => $cash_paid_to_office,
            'message' => 'Cash Paid Updated successfully.',
        ]);
    }

    public function cashpaidtoofficedelete(Request $request)
    {
        return 1;
    }

    //Method for to get denomination list
    public function cashPaidtoOfficeDenomination(){
        try {
            $denomination = DenominationType::orderby('value', 'ASC')->get();
            return response()->json([
                'status' => 200,
                'returnlists' => $denomination,
                'message' => 'Denomination fetched successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    //method for to get updated and updated dates
    public function lastUpdatedDatelist(Request $request)
    {
        try {
            $storeId = $request->store_id;
            $receiverId = $request->receiver_id;
            $lastUpdatedRecord = CashPaidToOffice::where('store_id', $storeId)
                                                    ->orderBy('updated_at', 'desc')
                                                    ->first();
            if (!$lastUpdatedRecord) {
                return response()->json([
                    'status' => 200,
                    'lastUpdatedDate' => Carbon::now()->format('Y-m-d'),
                    'notUpdatedDates' => [Carbon::now()->format('Y-m-d')],
                    'message' => 'Dates Fetched successfully',
                ]);
            }

            $lastUpdatedDate = Carbon::parse($lastUpdatedRecord->updated_at);
            $today = Carbon::now();
            $notUpdatedDates = [];

            $currentDate = $lastUpdatedDate->copy()->addDay();
            while ($currentDate <= $today) {
                $notUpdatedDates[] = $currentDate->format('Y-m-d');
                $currentDate->addDay();
            }

            return response()->json([
                'status' => 200,
                'lastUpdatedDate' => $lastUpdatedDate->format('Y-m-d'),
                'notUpdatedDates' => $notUpdatedDates,
                'message' => 'Dates Fetched successfully',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Something went wrong.',
            ], 500);
        }
    }

}
