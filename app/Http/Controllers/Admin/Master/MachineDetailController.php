<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\DataTables\Master\MachineDetailDataTable;
use App\Http\Requests\Master\MachineDetailFormRequest;
use App\Models\MachineData;
use App\Models\Store;
use App\Models\PLUMaster;

class MachineDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MachineDetailDataTable $dataTable)
    {
        return $dataTable->render('pages.master.machine_details.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['stores'] = Store::select('id', 'store_name', 'store_code')->active()->get();
        $data['plu_master_datas'] = PLUMaster::get();
        return view('pages.master.machine_details.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MachineDetailFormRequest $request)
    {
        // try {
        // return $request->all();
        $machine_details = new MachineData();
        $machine_details->MachineName = $request->machine_name;
        $machine_details->store_id = $request->store_id;
        $machine_details->Slno = $request->store_id ?? '';
        $machine_details->Port = $request->port;
        $machine_details->IPAddress = $request->ip_address;
        $machine_details->Capacity = $request->capacity;
        $machine_details->Status = $request->status;
        $machine_details->PLUMasterCode = $request->plu_master_code;
        $machine_details->Online = $request->machine_status;
        $machine_details->save();

        $machine_details->Slno = $machine_details->id;
        $machine_details->save();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('admin.machine-details.index')
                ->with('success', 'Machine Master Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Machine Master Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Machine Master Stored Fail');
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

            $data['machineDetails'] = MachineData::findOrFail($id);
            return view('pages.master.machine_details.view', $data);
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
        $data['stores'] = Store::select('id', 'store_name', 'store_code')->active()->get();
        $data['plu_master_datas'] = PLUMaster::get();
        $data['machine_details'] = MachineData::findOrFail($id);
        return view('pages.master.machine_details.edit', $data);
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
        // return $request->all();
        $machine_details = MachineData::findOrFail($id);
        $machine_details->MachineName = $request->machine_name;
        $machine_details->store_id = $request->store_id;
        $machine_details->Port = $request->port;
        $machine_details->IPAddress = $request->ip_address;
        $machine_details->Capacity = $request->capacity;
        $machine_details->Status = $request->status;
        $machine_details->PLUMasterCode = $request->plu_master_code;
        $machine_details->Online = $request->machine_status;
        $machine_details->save();
        $machine_details->Slno = $machine_details->id;
        $machine_details->save();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('admin.machine-details.index')
                ->with('success', 'Machine Master Updated Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Machine Master Updated Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Machine Master Updated Fail');
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
            MachineData::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Machine Detail Deleted Successfully.'
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
