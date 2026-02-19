<?php

namespace App\Http\Controllers\Admin\CashPaidToOffice;

use App\Core\CommonComponent;
use App\DataTables\StoreCashPaid\PaidToOfficeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashPaid\PaidToOfficeFormRequest;
use App\Models\Admin;
use App\Models\CashPaidToOffice;
use App\Models\CashRegisterTransaction;
use App\Models\Denomination;
use App\Models\DenominationDateAmount;
use App\Models\DenominationType;
use App\Models\Department;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Log;

class StoreCashPaidToOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaidToOfficeDataTable $dataTable)
    {
        return $dataTable->render('pages.store_cash_paid.paid_to_office.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $store = Store::all();
        $receivers = Admin::where('user_type', 1)->get();

        $data = [];

        foreach ($store as $stores) {
            foreach ($receivers as $receiver) {
                $cashPaidToOffice = CashPaidToOffice::where('store_id', $stores->id)
                    ->where('receiver_id', $receiver->id)
                    ->orderBy('updated_at', 'DESC')
                    ->first();

                if ($cashPaidToOffice) {
                    $lastUpdatedDate = $cashPaidToOffice->updated_at->format('Y-m-d');
                    $amount = $cashPaidToOffice->amount;
                } else {
                    $lastUpdatedDate = null;
                    $amount = null;
                }
                $data[] = [
                    'store_id' => $stores->id,
                    'receiver_id' => $receiver->id,
                    'lastUpdatedDate' => $lastUpdatedDate,
                    'amount' => $amount,
                ];
            }
        }

        $denominations = DenominationType::orderby('type', 'asc')->get();

        $viewData = [
            'data' => $data,
            'denominations' => $denominations,
            'store' => $store,
            'receivers' => $receivers,
            'lastUpdatedDate' => $lastUpdatedDate,
        ];

        return view('pages.store_cash_paid.paid_to_office.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaidToOfficeFormRequest $request)
    {
        try {
            DB::beginTransaction();
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
            if ($request->hasFile('signature')) {
                $signatureData = CommonComponent::s3BucketFileUpload($request->signature, 'signature');
                $signaturePath = $signatureData['filePath'];
                $signatureUrl = $signatureData['imageURL'];
            }
            $cash_paid_to_office = new CashPaidToOffice();
            $cash_paid_to_office->store_id = $request->store_id;
            $cash_paid_to_office->amount = (int) $request->amount;
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
            $cash_paid_to_office->status = $request->status;
            $cash_paid_to_office->save();

            $totalAmount = (int) $request->total;
            if ($cash_paid_to_office->amount !== $totalAmount) {
                return back()->withInput()->with('error', 'Amount and total must be equal');
            }

            $cash_paid_to_office_id = $cash_paid_to_office->id;
            //to store cash paid to office information in denomination
            foreach ($request->denominations as $key => $denomination) {
                Denomination::create([
                    'store_id' => $request->store_id,
                    'cash_paid_id' => $cash_paid_to_office_id,
                    'denomination_id' => $key,
                    'denomination_value' => $denomination ?? 0,
                    'amount' => (int) $request->amount,
                    'total_amount' => $totalAmount,
                ]);
            }

            foreach ($request->dates as $key => $date) {
                DenominationDateAmount::create([
                    'dates' => $date,
                    'amount' => $request->amounts[$key],
                    'cash_paid_id' => $cash_paid_to_office_id,
                ]);
            }

            $cash_register_transaction = new CashRegisterTransaction();
            $cash_register_transaction->store_id = $request->store_id;
            $cash_register_transaction->payment_type_id = 1;
            $cash_register_transaction->amount = (int) $request->amount;
            $cash_register_transaction->transaction_datetime = Carbon::now();
            $cash_register_transaction->type = 1; // Credit
            $cash_register_transaction->note = $request->note;
            $cash_register_transaction->save();
            DB::commit();
            if ($request->submission_type == 1) {
                return redirect()->route('admin.cash-paid-office.index')->with('success', 'Cash Paid Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Cash Paid Store Successfully');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return back()->withInput()->with('error', 'Cash Paid Stored Fail');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $data['cash_paid_office'] = CashPaidToOffice::findOrFail($id);
            $data['store'] = Store::all();
            $data['admin'] = Admin::all();
            return view('pages.store_cash_paid.paid_to_office.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cashPaidToOffice = CashPaidToOffice::findOrFail($id);

        $stores = Store::all();

        $receivers = Admin::where('user_type', 1)->get();

        $denominations = DenominationType::orderby('type', 'ASC')->get();

        $lastUpdatedDate = $cashPaidToOffice->updated_at->format('Y-m-d');

        $data = [
            'cashPaidTooffice' => $cashPaidToOffice,
            'store' => $stores,
            'receiver' => $receivers,
            'lastUpdatedDate' => $lastUpdatedDate,
            'denominations' => $denominations,
        ];
        return view('pages.store_cash_paid.paid_to_office.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'store_id' => 'required|integer',
            // 'amount' => 'required|integer',
            'payer_id' => 'nullable|integer',
            'receiver_id' => 'required|integer',
            'status' => 'required|string',
            // 'dates' => 'required|array',
            // 'dates.*' => 'required|date',
            // 'amounts' => 'required|array',
            // 'amounts.*' => 'required|numeric',
        ]);

        // try {
        $cash_paid_to_office = CashPaidToOffice::findOrFail($id);

        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('file')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->file, 'cash_paid_docs');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $signaturePath = null;
        $signatureUrl = null;
        if ($request->hasFile('signature')) {
            $signatureData = CommonComponent::s3BucketFileUpload($request->signature, 'signature');
            $signaturePath = $signatureData['filePath'];
            $signatureUrl = $signatureData['imageURL'];
        }

        $cash_paid_to_office->store_id = $request->store_id;
        $cash_paid_to_office->amount = (int) $request->amount;
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
        $cash_paid_to_office->status = $request->status;
        $cash_paid_to_office->save();

        $cash_denominations = Denomination::findOrFail($id);

        $totalAmount = (int) $request->total;
        if ($cash_paid_to_office->amount !== $totalAmount) {
            return back()->withInput()->with('error', 'Amount and total must be equal');
        }

        $cash_paid_to_office_id = $cash_paid_to_office->id;
        //to store cash paid to office information in denomination
        foreach ($request->denominations as $key => $denomination) {

            Denomination::updateOrCreate(
                [
                    'store_id' => $request->store_id,
                    'cash_paid_id' => $cash_paid_to_office_id,
                    'denomination_id' => $key,
                ],
                [
                    'denomination_value' => $denomination,
                    'amount' => (int) $request->amount,
                    'total_amount' => $totalAmount,
                ]
            );
        }

        foreach ($request->dates as $key => $date) {
            DenominationDateAmount::updateOrCreate(
                [
                    'dates' => $date,
                    'cash_paid_id' => $cash_paid_to_office_id,
                ],
                [
                    'amount' => $request->amounts[$key],
                ]
            );
        }

        return redirect()->route('admin.cash-paid-office.index')->with('success', 'Cash Paid Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Cash Paid Updated Failed');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Department::findOrFail($id)->delete();

            return redirect()->route('admin.cash-paid-office.index')->with('success', 'Cash Paid Deleted Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    public function getPayerDetails(Request $request)
    {
        try {
            $store_id = $request->store_id;
            Log::info("store_idstore_id");
            Log::info($store_id);
            $store_employees = Admin::when($request, function ($query) use ($store_id) {
                $query->LeftJoin('partnership_details', function ($join) use ($store_id) {
                    $join->on('partnership_details.partner_id', 'admins.id')
                        ->whereNull('partnership_details.deleted_at')
                        ->where([['partnership_details.store_id', $store_id], ['partnership_details.status', 1]]);
                })
                    ->Orwhere([['partnership_details.store_id', $store_id], ['partnership_details.status', 1]]);

                $query->LeftJoin('staff_store_mappings', function ($join) use ($store_id) {
                    $join->on('staff_store_mappings.staff_id', 'admins.id')
                        ->whereNull('staff_store_mappings.deleted_at')
                        ->where([['staff_store_mappings.store_id', $store_id], ['staff_store_mappings.status', 1]]);
                })
                    ->Orwhere([['staff_store_mappings.store_id', $store_id], ['staff_store_mappings.status', 1]]);
            })
                ->distinct('admins.id')
                ->select('admins.*')
                ->get();
            Log::info('store_employees');
            Log::info($store_employees);
            return response()->json([
                'status' => 200,
                'data' => $store_employees,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong',
            ]);
        }
    }

    //method for to return not updated dates
    public function getNotUpdatedDates($id)
    {
        try {
            $cashPaidToOffice = CashPaidToOffice::findOrFail($id);

            $lastUpdatedDate = Carbon::parse($cashPaidToOffice->updated_at);

            $notUpdatedDates = [];
            $today = Carbon::now();
            $currentDate = $lastUpdatedDate->copy()->addDay();

            while ($currentDate <= $today) {
                $notUpdatedDates[] = $currentDate->format('Y-m-d');
                $currentDate->addDay();
            }

            return response()->json([
                'notUpdatedDates' => $notUpdatedDates,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Something went wrong.',
            ], 500);
        }
    }

    //method for to get updated and updated dates
    public function getUpdatedDates(Request $request)
    {
        $storeId = $request->store_id;
        $receiverId = $request->receiver_id;

        $lastUpdatedRecord = CashPaidToOffice::where('store_id', $storeId)
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!$lastUpdatedRecord) {
            return response()->json([
                'status' => 200,
                'lastUpdatedDate' => 000 - 00 - 00,
                'notUpdatedDates' => [Carbon::now()->format('Y-m-d')],

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
            'lastUpdatedDate' => $lastUpdatedDate->format('Y-m-d'),
            'notUpdatedDates' => $notUpdatedDates,
        ]);
    }

}
