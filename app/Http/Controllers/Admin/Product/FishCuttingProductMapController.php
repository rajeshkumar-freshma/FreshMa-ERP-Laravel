<?php

namespace App\Http\Controllers\Admin\Product;

use App\DataTables\Product\FishCuttingProductMapDataTable;
use App\Http\Controllers\Controller;
use App\Models\FishCuttingProductMap;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FishCuttingProductMapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FishCuttingProductMapDataTable $dataTable)
    {
        return $dataTable->render('pages.product.fish_cutting_product_map.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['products'] = Product::orderBy('id', 'ASC')->get();
        return view('pages.product.fish_cutting_product_map.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        // try {
            $fish_cutting_data = new FishCuttingProductMap();
            $fish_cutting_data->main_product_id = $request->main_product_id;
            $fish_cutting_data->status = $request->status;
            $fish_cutting_data->grouped_product = json_encode($request->products);
            $fish_cutting_data->wastage_percentage = $request->wastage_percentage ?? 0;
            $fish_cutting_data->remarks = $request->remarks;
            $fish_cutting_data->save();
            DB::commit();

            if ($request->submission_type == 1) {
                return redirect()
                    ->route('admin.fish-cutting-product-map.index')
                    ->with('success', 'Product Mapped Successfully');
            } elseif ($request->submission_type == 2) {
                return back()->with('success', 'Product Mapped Successfully');
            }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Product Mapped Fail');
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
        $data['fish_cutting_data'] = FishCuttingProductMap::find($id);
        $data['products'] = Product::orderBy('id', 'ASC')->get();
        return view('pages.product.fish_cutting_product_map.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return json_encode($request->products);
        // return $request;
        DB::beginTransaction();
        try {
            $fish_cutting_data = FishCuttingProductMap::find($id);
            $fish_cutting_data->main_product_id = $request->main_product_id;
            $fish_cutting_data->status = $request->status;
            $fish_cutting_data->grouped_product = json_encode($request->products);
            $fish_cutting_data->wastage_percentage = $request->wastage_percentage ?? 0;
            $fish_cutting_data->remarks = $request->remarks;
            $fish_cutting_data->save();
            DB::commit();

            return redirect()
                ->route('admin.fish-cutting-product-map.index')
                ->with('success', 'Product Mapped Updated Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->withInput()->with('error', 'Product Mapped Fail');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
