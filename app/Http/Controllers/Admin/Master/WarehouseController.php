<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\WarehouseDataTable;
use App\Http\Requests\Master\WarehouseFormRequest;
use App\Models\Warehouse;
use App\Models\Country;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WarehouseDataTable $dataTable)
    {
        return $dataTable->render('pages.master.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $data['countries'] = Country::where('status', 1)->get();
        return view('pages.master.warehouse.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseFormRequest $request)
    {
        // try {
        $slug = commoncomponent()->slugCreate($request->name, $request->slug);

        if ($request->is_default == 1 && $request->status == 0) {
            return back()->with('warning', "Your Couldn't disable and default in a same action.");
        } elseif ($request->is_default == 1) {
            $warehousedefaultcheck = Warehouse::where('is_default', 1)->get();
            if (count($warehousedefaultcheck) > 0) {
                Warehouse::where('is_default', 1)->update(['is_default', 0]);
            }
        }

        $warehouse = new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->slug = $slug;
        $warehouse->code = $request->code;
        $warehouse->phone_number = $request->phone_number;
        $warehouse->email = $request->email;
        $warehouse->start_date = $request->start_date;
        $warehouse->address = $request->address;
        $warehouse->city_id = $request->city_id;
        $warehouse->state_id = $request->state_id;
        $warehouse->country_id = $request->country_id;
        $warehouse->pincode = $request->pincode;
        $warehouse->longitude = $request->longitude;
        $warehouse->latitude = $request->latitude;
        $warehouse->direction = $request->direction;
        $warehouse->status = $request->status;
        $warehouse->is_default = $request->is_default != null ? $request->is_default : 0;
        $warehouse->save();


        if ($request->submission_type == 1) {
            return redirect()->route('admin.warehouse.index')->with('success', 'Warehouse Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Warehouse Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Warehouse Updated Fail');
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
            $data['warehouse'] = Warehouse::findOrFail($id);
            $data['countries'] = Country::where('status', 1)->get();
            return view('pages.master.warehouse.view', $data);
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
        $data['warehouse'] = Warehouse::findOrFail($id);
        $data['countries'] = Country::where('status', 1)->get();
        return view('pages.master.warehouse.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseFormRequest $request, $id)
    {
        // try {
        $slug = commoncomponent()->slugCreate($request->name, $request->slug);

        if ($request->is_default == 0) {
            $warehousedefaultcheck = Warehouse::where('id', $id)->where('is_default', 1)->get();
            if (count($warehousedefaultcheck) == 1) {
                return back()->with('warning', "Your Couldn't disable the default branch");
            }
        }

        if ($request->is_default == 1) {
            $warehousedefaultcheck = Warehouse::where('is_default', 1)->get();
            if (count($warehousedefaultcheck) > 0) {
                foreach ($warehousedefaultcheck as $warehouse) {
                    $warehouse->update(['is_default' => 0]);
                }
            }
        }

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->name = $request->name;
        $warehouse->slug = $slug;
        $warehouse->code = $request->code;
        $warehouse->phone_number = $request->phone_number;
        $warehouse->email = $request->email;
        $warehouse->start_date = $request->start_date;
        $warehouse->address = $request->address;
        $warehouse->city_id = $request->city_id;
        $warehouse->state_id = $request->state_id;
        $warehouse->country_id = $request->country_id;
        $warehouse->pincode = $request->pincode;
        $warehouse->longitude = $request->longitude;
        $warehouse->latitude = $request->latitude;
        $warehouse->direction = $request->direction;
        $warehouse->status = $request->status;
        $warehouse->is_default = $request->is_default != null ? $request->is_default : 0;
        $warehouse->save();

        return redirect()->route('admin.warehouse.index')->with('success', 'Warehouse Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Warehouse Updated Fail');
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
            Warehouse::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Warehouse Deleted Successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }

    public function warehouseDefaultStatus(Request $request)
    {
        if (isset($request->status_value)) {
            $warehouse_id = $request->warehouse_id;
            $status_value = $request->status_value;
            Log::info($status_value);
            Warehouse::where('id', $warehouse_id)->update(['status' => $status_value]);
            return response()->json([
                'status' => 200,
                'message' => "Status update Successfully",

            ]);
        }
    }

    public function defaultWarehouseUpdate(Request $request)
    {
        if ($request->value == 0) {
            return response()->json([
                'status' => 400,
                'message' => "Your Couldn't disabled the active default Warehouse."
            ]);
        }
        if (isset($request->default) && $request->default == 1) {
            if (Warehouse::where([['id', $request->warehouse_id], ['status', 0]])->exists()) {
                return response()->json([
                    'status' => 400,
                    'message' => "This Warehouse was disabled, Your Couldn't Make the default Warehouse."
                ]);
            }
            Warehouse::where('id', '>', 0)->update(['is_default' => 0]);
            Warehouse::where('id', $request->warehouse_id)->update([
                'is_default' => 1,
            ]);

            return response()->json([
                'status' => 200,
                'message' => "Default Warehouse Updated Successfully."
            ]);
        }
    }
}
