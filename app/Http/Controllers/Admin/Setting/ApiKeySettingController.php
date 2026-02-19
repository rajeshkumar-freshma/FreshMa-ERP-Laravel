<?php

namespace App\Http\Controllers\Admin\Setting;


use App\Http\Controllers\Controller;
use App\DataTables\Setting\ApiKeySettingDataTable;
use App\Http\Requests\Setting\ApiKeySettingFormRequest;
use App\Models\ApiKeySetting;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

class ApiKeySettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ApiKeySettingDataTable $dataTable)
    {
        $data['api_key_settings_data'] = ApiKeySetting::get();
        return $dataTable->render('pages.setting.api_key_setting.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApiKeySettingFormRequest $request)
    {
        DB::beginTransaction();
        // try {
        // Retrieve the values from the request
        $name = $request->name;
        $api_key = $request->api_key;
        $status = $request->status;

        // Check if all required values are provided

        // Create or update the system setting
        $apiKeySetting = ApiKeySetting::firstOrNew(['api_key' => $api_key]);
        $apiKeySetting->name = $name;
        $apiKeySetting->status = $status;
        $apiKeySetting->save();

        DB::commit();
        //         // Redirect back with success message
        return to_route('admin.api-keys.index')->with('success', 'Api Keys Stored Successfully');
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Api Key Cannot be Created')->withInput();
        // }
    }



    /**
     * Display the specified resource.
     */
    public function show(ApiKeySetting $apiKeySetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $data['apiKeySetting'] = ApiKeySetting::findOrFail($id);
            return view('pages.setting.api_key_setting.edit', $data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ApiKeySettingFormRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // Check if the ApiKeySetting exists
            $apiKeySetting = ApiKeySetting::find($id);
            if (!$apiKeySetting) {
                return back()->with('error', 'Api Key Setting not found');
            }

            // Retrieve the values from the validated request
            $api_key = $request->api_key;
            $name = $request->name;
            $status = $request->status;

            // Update the ApiKeySetting
            $apiKeySetting->api_key = $api_key;
            $apiKeySetting->name = $name;
            $apiKeySetting->status = $status;
            $apiKeySetting->save();

            DB::commit();

            // Redirect back with success message
            return redirect()->route('admin.api-keys.index')->with('success', 'Api Keys Stored Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating Api Key')->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiKeySetting $apiKeySetting)
    {
        //
    }
}
