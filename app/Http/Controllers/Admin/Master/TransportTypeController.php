<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\TransportTypeDataTable;
use App\Http\Requests\Master\TransportTypeFormRequest;
use App\Models\TransportType;

class TransportTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransportTypeDataTable $dataTable)
    {
        return $dataTable->render('pages.master.transport_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.master.transport_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        $transport_type = new TransportType();
        $transport_type->transport_type = $request->transport_type;
        $transport_type->status = $request->status;
        $transport_type->save();

        if ($request->submission_type == 1) {
            return redirect()->route('admin.transport-type.index')->with('success', 'Transport Type Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Transport Type Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Transport Type Stored Fail');
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

            $data['transportType'] = TransportType::findOrFail($id);
            return view('pages.master.transport_type.view', $data);
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
        $data['transport_type'] = TransportType::findOrFail($id);
        return view('pages.master.transport_type.edit', $data);
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
        // try {
        $transport_type = TransportType::findOrfail($id);
        $transport_type->transport_type = $request->transport_type;
        $transport_type->status = $request->status;
        $transport_type->save();

        return redirect()->route('admin.transport-type.index')->with('success', 'Transport Type updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Transport Type updated Fail');
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
            TransportType::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Transport Type Deleted Successfully.'
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
