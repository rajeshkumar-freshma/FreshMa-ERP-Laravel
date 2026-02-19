<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\ItemTypeDataTable;
use App\Http\Requests\Master\ItemTypeFormRequest;
use App\Models\ItemType;

class ItemTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ItemTypeDataTable $dataTable)
    {
        return $dataTable->render('pages.master.item_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.master.item_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemTypeFormRequest $request)
    {
        // try {
            $itemType = new ItemType();
            $itemType->name = $request->name;
            $itemType->status = $request->status;
            $itemType->save();

            if ($request->submission_type == 1) {
                return redirect()->route('admin.item-type.index')->with('success', 'Item Type Store Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Item Type Store Successfully');
            }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Item Type Stored Fail');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['item_type'] = ItemType::findOrFail($id);
        return view('pages.master.item_type.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemTypeFormRequest $request, $id)
    {
        // try {
            $itemType = ItemType::findOrFail($id);
            $itemType->name = $request->name;
            $itemType->status = $request->status;
            $itemType->save();

            return redirect()->route('admin.item-type.index')->with('success', 'Item Type Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Item Type Updated Fail');
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
            ItemType::findOrFail($id)->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Item Type Deleted Successfully.'
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
