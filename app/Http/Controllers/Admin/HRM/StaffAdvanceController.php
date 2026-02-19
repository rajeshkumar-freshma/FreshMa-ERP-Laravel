<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Core\CommonComponent;
use App\DataTables\HRM\StaffAdvanceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\StaffAdvanceFormRequest;
use App\Models\PaymentTransaction;
use App\Models\PaymentType;
use App\Models\Staff;
use App\Models\StaffAdvance;
use App\Models\StaffAdvanceHistory;
use Illuminate\Support\Facades\DB;

class StaffAdvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StaffAdvanceDataTable $dataTable)
    {
        return $dataTable->render('pages.hrm.staff_advance.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['paymentTypes'] = PaymentType::where('status', 1)->get();
        $data['Staff'] = Staff::where('status', 1)->get();
        return view('pages.hrm.staff_advance.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffAdvanceFormRequest $request)
    {
        // DB::beginTransaction();
        // try {
        // return $request->all();
        $user_advance = StaffAdvance::where([['staff_id', $request->user_id]])->first();
        if ($user_advance == null) {
            $user_advance = new StaffAdvance();
        }
        $user_advance->staff_id = $request->user_id;
        $user_advance->type = 1; // Credit
        $user_advance->amount = $request->amount;
        $user_advance->payment_type_id = $request->payment_type_id;
        $user_advance->date = $request->date;
        $user_advance->status = $request->status;
        $user_advance->note = $request->notes;
        $user_advance->total_amount = @$user_advance->total_amount + $request->amount;
        $user_advance->save();

        $advancehistory = new StaffAdvanceHistory();
        $advancehistory->staff_id = $request->user_id;
        $advancehistory->staff_advance_id = $user_advance->id;
        $advancehistory->type = 1; // Credit
        $advancehistory->amount = $request->amount;
        $advancehistory->status = $request->status;
        $advancehistory->note = $request->notes;

        if ($request->hasFile('staff_advance_documents')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->staff_advance_documents, 'staff_advance_doc');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];

            if ($imageUrl !== null) {
                $advancehistory->file = $imageUrl;
                $advancehistory->file_path = $imagePath;
            }
        }
        $advancehistory->save();

        $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction'); // Auto Generate
        $payment_transaction->transaction_type = 6; // Advance
        $payment_transaction->type = 2; // Debit
        $payment_transaction->reference_id = $advancehistory->id;
        $payment_transaction->payment_type_id = (int) $request->payment_type_id;
        $payment_transaction->amount = $request->amount;
        $payment_transaction->transaction_datetime = $request->transaction_datetime;
        $payment_transaction->status = 1;
        $payment_transaction->note = @$request->notes;
        $payment_transaction->save();

        if (isset($request->staff_advance_doc) && count($request->staff_advance_doc) > 0 && $request->file('staff_advance_doc')) {
            CommonComponent::payment_transaction_documents($request->file('staff_advance_doc'), 5, $payment_transaction->id); // 3 => User Advance
        }

        // Transaport Tracking Docs Store
        // if (isset ($request->user_advance_images) && count($request->user_advance_images) > 0 && $request->file('user_advance_images')) {
        //     foreach ($request->file('user_advance_images') as $key => $value) {
        //         if ($value) {
        //             $imagePath = null;
        //             $imageUrl = null;
        //             $imageData = CommonComponent::s3BucketFileUpload($value, 'staff_advance_doc');
        //             $imagePath = $imageData['filePath'];
        //             // $imageUrl = $imageData['fileName'];
        //             $imageUrl = $imageData['imageURL'];

        //             $purchase_order_docs = new PurchaseSalesDocument();
        //             $purchase_order_docs->type = 6; // User Advance
        //             $purchase_order_docs->reference_id = $advancehistory->id;
        //             $purchase_order_docs->document_type = 1; // Expense (User Advance)
        //             $purchase_order_docs->file = @$imageUrl;
        //             $purchase_order_docs->file_path = @$imagePath;
        //             $purchase_order_docs->save();
        //         }
        //     }
        // }

        DB::commit();
        // Redirect based on submission type
        if ($request->submission_type == 1) {
            return redirect()->route('admin.staff-advance.index')->with('success', 'Staff Advance Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Staff Advance Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Staff Advance Store Failed');
        // }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['paymentTypes'] = PaymentType::where('status', 1)->get();
        $data['Staff'] = Staff::where('status', 1)->get();
        $data['staff_advanced'] = StaffAdvance::findOrFail($id);
        return view('pages.hrm.staff_advance.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StaffAdvanceFormRequest $request, $id)
    {
        // try {
        // Find or create staff advance record
        $user_advance = StaffAdvance::updateOrCreate(
            ['id' => $id],
            [
                'staff_id' => $request->user_id,
                'type' => 1, // Credit
                'amount' => $request->amount,
                'date' => $request->date,
                'status' => $request->status,
                'note' => $request->notes,
            ]
        );

        // Update staff advance history
        $advancehistory = StaffAdvanceHistory::updateOrCreate(
            ['staff_advance_id' => $user_advance->id],
            [
                'staff_id' => $request->user_id,
                'type' => 1, // Credit
                'amount' => $request->amount,
                'status' => $request->status,
                'note' => $request->notes,
            ]
        );

        // Upload documents if provided
        if ($request->hasFile('staff_advance_documents')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->staff_advance_documents, 'staff_advance_doc');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];

            if ($imageUrl !== null) {
                $advancehistory->file = $imageUrl;
                $advancehistory->file_path = $imagePath;
                $advancehistory->save();
            }
        }

        // Create payment transaction
        $payment_transaction = new PaymentTransaction();
        $payment_transaction->transaction_number = CommonComponent::invoice_no('payment_transaction');
        $payment_transaction->transaction_type = 6; // Advance
        $payment_transaction->type = 2; // Debit
        $payment_transaction->reference_id = $advancehistory->id;
        $payment_transaction->payment_type_id = (int) $request->payment_type_id;
        $payment_transaction->amount = $request->amount;
        $payment_transaction->transaction_datetime = $request->transaction_datetime;
        $payment_transaction->status = 1;
        $payment_transaction->note = $request->notes;
        $payment_transaction->save();

        // Upload transaction documents if provided
        if ($request->hasFile('staff_advance_doc')) {
            CommonComponent::payment_transaction_documents($request->file('staff_advance_doc'), 5, $payment_transaction->id);
        }

        // Redirect based on submission type
        if ($request->submission_type == 1) {
            return redirect()->route('admin.staff-advance.index')->with('success', 'Staff Advance Update Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Staff Advance Update Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Staff Advance Update Failed');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
