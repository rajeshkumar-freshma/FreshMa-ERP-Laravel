<?php

namespace App\Http\Controllers\Admin\Master;

use App\DataTables\Master\PaymentTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\PaymentTypeRequestForm;
use App\Models\PaymentType;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentTypeDataTable $dataTable)
    {
        return $dataTable->render('pages.master.paymenttype.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['stores'] = Store::where('status', 1)->get();
        return view('pages.master.paymenttype.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentTypeRequestForm $request)
    {
        // try {
        $slug = commoncomponent()->slugCreate($request->payment_type, $request->slug);
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('icon')) {
            $imageData = commoncomponent()->s3BucketFileUpload($request->icon, 'payment_type');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $payment_type = new PaymentType();
        $payment_type->payment_type = $request->payment_type;
        $payment_type->slug = $slug;
        $payment_type->payment_category = $request->payment_category;
        $payment_type->status = $request->status;
        $payment_type->machine_number = $request->machine_number;
        $payment_type->store_id = $request->store_id;
        if ($imageUrl != null) {
            $payment_type->file = $imageUrl;
            $payment_type->file_path = $imagePath;
        }
        $payment_type->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.payment-type.index')->with('success', 'Payment Type Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Payment Type Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Payment Type Stored Fail');
        // }
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

            $data['paymentType'] = PaymentType::findOrFail($id);
            $data['stores'] = Store::all();
            return view('pages.master.paymenttype.view', $data);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
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
        $data['paymenttype'] = PaymentType::findOrFail($id);
        $data['stores'] = Store::where('status', 1)->get();
        return view('pages.master.paymenttype.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentTypeRequestForm $request, $id)
    {
        // try {
        $slug = commoncomponent()->slugCreate($request->payment_type, $request->slug);

        $payment_type = PaymentType::findOrFail($id);
        $imagePath = $payment_type->file_path;
        $imageUrl = $payment_type->file;
        if ($request->hasFile('icon')) {
            $imageData = commoncomponent()->s3BucketFileUpload($request->icon, 'payment_type');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $payment_type->payment_type = $request->payment_type;
        $payment_type->slug = $slug;
        $payment_type->payment_category = $request->payment_category;
        $payment_type->status = $request->status;
        $payment_type->machine_number = $request->machine_number;
        $payment_type->store_id = $request->store_id;
        if ($imageUrl != null) {
            $payment_type->file = $imageUrl;
            $payment_type->file_path = $imagePath;
        }
        $payment_type->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.payment-type.index')->with('success', 'Payment Type Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Payment Type Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Payment Type Stored Fail');
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
            PaymentType::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Store Deleted Successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }
}
