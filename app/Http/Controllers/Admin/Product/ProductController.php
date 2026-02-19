<?php

namespace App\Http\Controllers\Admin\Product;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Product\ProductDataTable;
use App\Http\Requests\Product\ProductFormRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\ItemType;
use App\Models\Unit;
use App\Models\TaxRate;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\StoreIndentRequest;
use App\Models\StoreInventoryDetail;
use App\Models\WarehouseIndentRequest;
use App\Models\WarehouseIndentRequestDetail;
use App\Models\WarehouseInventoryDetail;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('pages.product.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = Category::where('status', 1)->get();
        $data['item_types'] = ItemType::where('status', 1)->get();
        $data['units'] = Unit::where('status', 1)->get();
        $data['tax_rates'] = TaxRate::where('status', 1)->get();
        return view('pages.product.product.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductFormRequest $request)
    {
        DB::beginTransaction();
        // try {
        $slug = CommonComponent::slugCreate($request->name, $request->slug);

        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('image')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'product');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->sku_code = $request->sku_code;
        $product->hsn_code = $request->hsn_code;
        $product->product_description = $request->description;
        $product->status = $request->status;
        $product->item_type_id = $request->item_type_id;
        $product->unit_id = $request->unit_id;
        $product->tax_type = $request->tax_type;
        $product->tax_id = $request->tax_id;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;
        if ($imageUrl != null) {
            $product->image = $imageUrl;
            $product->image_path = $imagePath;
        }
        $product->save();

        if (count($request->category_id) > 0) {
            foreach ($request->category_id as $key => $value) {
                $product_category = new ProductCategory();
                $product_category->product_id = $product->id;
                $product_category->category_id = $value;
                $product_category->save();
            }
        }

        if (isset($request->product_images) && count($request->product_images) > 0) {
            foreach ($request->product_images as $key => $value) {
                $imageData = CommonComponent::s3BucketFileUpload($value, 'product');
                $imagePath = $imageData['filePath'];
                $imageUrl = $imageData['imageURL'];

                $product_image = new ProductImage();
                $product_image->product_id = $product->id;
                $product_image->image_path = $imagePath;
                $product_image->image = $imageUrl;
                $product_image->save();
            }
        }
        DB::commit();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('admin.product.index')
                ->with('success', 'Product Stored Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Product Stored Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Product Stored Fail');
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
        $data = $common_data = $this->product_overview($id);
        return view('pages.product.product.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['categories'] = Category::where('status', 1)->get();
        $data['item_types'] = ItemType::where('status', 1)->get();
        $data['units'] = Unit::where('status', 1)->get();
        $data['tax_rates'] = TaxRate::where('status', 1)->get();
        $data['product'] = Product::findOrFail($id);
        return view('pages.product.product.edit', $data);
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
        // return $request;
        DB::beginTransaction();
        // try {
        $slug = CommonComponent::slugCreate($request->name, $request->slug);

        $imagePath = null;
        $imageUrl = null;

        $product = Product::findOrfail($id);

        if ($request->hasFile('image')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($product->image, $product->image_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'product');
            $imagePath = $imageData['filePath'];
            $imageUrl = $imageData['imageURL'];
        }

        $product->name = $request->name;
        $product->slug = $slug;
        $product->sku_code = $request->sku_code;
        $product->hsn_code = $request->hsn_code;
        $product->product_description = $request->description;
        $product->status = $request->status;
        $product->item_type_id = $request->item_type_id;
        $product->unit_id = $request->unit_id;
        $product->tax_type = $request->tax_type;
        $product->tax_id = $request->tax_id;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;
        if ($imageUrl != null) {
            $product->image = $imageUrl;
            $product->image_path = $imagePath;
        }
        $product->save();

        if (count($request->category_id) > 0) {
            $exists_product_category = ProductCategory::where('product_id', $id)->get();
            $exists_category_ids = $exists_product_category->pluck('category_id')->toArray();
            foreach ($exists_product_category as $key => $value) {
                if (!in_array($value->category_id, $request->category_id)) {
                    ProductCategory::destroy($value->id);
                }
            }

            foreach ($request->category_id as $key => $value) {
                if (!in_array($value, $exists_category_ids)) {
                    $product_category = new ProductCategory();
                    $product_category->product_id = $product->id;
                    $product_category->category_id = $value;
                    $product_category->save();
                }
            }
        }

        if (isset($request->product_images) && count($request->product_images) > 0) {
            foreach ($request->product_images as $key => $value) {
                $imageData = CommonComponent::s3BucketFileUpload($value, 'product');
                $imagePath = $imageData['filePath'];
                $imageUrl = $imageData['imageURL'];

                $product_image = new ProductImage();
                $product_image->product_id = $product->id;
                $product_image->image_path = $imagePath;
                $product_image->image = $imageUrl;
                $product_image->save();
            }
        }
        DB::commit();

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Product Updated Fail');
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
            $product = Product::findOrFail($id);

            // $fileDeleted = CommonComponent::s3BucketFileDelete($product->image, $product->image_path);

            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Product Deleted Successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }

    // product base get purchse order details
    public function product_purchase($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['purchase_tables'] = PurchaseOrder::leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
            ->leftJoin('users', 'purchase_orders.supplier_id', '=', 'users.id') //left Join with users table to get supplier
            ->where('users.user_type',2)
            ->where('purchase_order_details.product_id', '=', $id)
            ->select(
                'purchase_orders.purchase_order_number',
                'purchase_orders.delivery_date',
                'purchase_orders.supplier_id',
                'purchase_order_details.request_quantity',
                'purchase_order_details.amount',
                'purchase_order_details.given_quantity',
                'purchase_orders.created_at',
                'users.first_name as supplier_name'
            )->orderBy('purchase_orders.id', 'desc')
            ->paginate(10);
        return view('pages.product.product.purchasetable', $data);
    }

    // product base get sales order details
    public function product_sales($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['sales_tables'] = SalesOrder::leftJoin('sales_order_details', 'sales_orders.id', '=', 'sales_order_details.sales_order_id')
            ->leftJoin('users', 'sales_orders.vendor_id', '=', 'users.id') //left Join with users table to get supplier
            ->where('users.user_type',1)
            ->where('sales_order_details.product_id', '=', $id)
            ->select(
                'sales_orders.invoice_number',
                'sales_orders.delivered_date',
                'sales_orders.vendor_id',
                'sales_orders.total_request_quantity',
                'sales_orders.total_amount',
                'sales_order_details.request_quantity',
                'sales_orders.created_at',
                'users.first_name as vendor_name'
            )
            ->paginate(10);
        return view('pages.product.product.salestable', $data);
    }

    // product base get store intent details
    public function product_store_intent_request($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['store_intent_tables'] = StoreIndentRequest::leftJoin('store_indent_request_details', 'store_indent_requests.id', '=', 'store_indent_request_details.store_indent_request_id')
            ->leftJoin('stores', 'store_indent_requests.store_id', '=', 'stores.id') // Join with the stores table
            ->where('store_indent_request_details.product_id', '=', $id)
            ->select(
                'store_indent_requests.request_code',
                'store_indent_requests.request_date',
                'store_indent_requests.expected_date',
                'store_indent_requests.store_id',
                'store_indent_requests.total_request_quantity',
                'store_indent_request_details.given_quantity',
                'store_indent_requests.created_at',
                'stores.store_name as store_name'
            )->orderBy('store_indent_requests.id', 'desc')
            ->paginate(10);
        return view('pages.product.product.storeintentrequest', $data);
    }

    // product base get warehouse intent details
    public function product_warehouse_intent_request($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['warehouse_intent_tables'] = WarehouseIndentRequest::leftJoin('warehouse_indent_request_details', 'warehouse_indent_requests.id', '=', 'warehouse_indent_request_details.warehouse_ir_id')
            ->leftJoin('warehouses', 'warehouse_indent_requests.warehouse_id', '=', 'warehouses.id') // Join with the stores table
            ->leftJoin('users', 'warehouse_indent_requests.supplier_id', '=', 'users.id') //left Join with users table to get supplier
            ->where('users.user_type',2)
            ->where('warehouse_indent_request_details.product_id', '=', $id)
            ->select(
                'warehouse_indent_requests.request_code',
                'warehouse_indent_requests.warehouse_id',
                'warehouse_indent_requests.supplier_id',
                'warehouse_indent_requests.total_request_quantity',
                'warehouse_indent_request_details.given_quantity',
                'warehouse_indent_requests.request_date',
                'warehouse_indent_requests.expected_date',
                'warehouse_indent_requests.created_at',
                'warehouse_indent_requests.total_amount',
                'warehouse_indent_request_details.amount',
                'warehouses.name as warehouse_name',
                'users.first_name as supplier_name'
            )->orderBy('warehouse_indent_requests.id', 'desc')
            ->paginate(10);
        // dd($data);
        return view('pages.product.product.warehouserequest', $data);
    }

    public function product_overview($id)
    {
        $data['product'] = Product::findOrFail($id);
        $data['purchase_amount'] = PurchaseOrderDetail::where('product_id', $id)->sum('amount');
        $data['sales_amount'] = SalesOrderDetail::where('product_id', $id)->sum('total');
        $data['warehouse_amount'] = WarehouseIndentRequestDetail::where('product_id', $id)->sum('amount');
        return $data;
    }

    // Store Stock
    public function store_stock($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['store_deatils'] = StoreInventoryDetail::leftJoin('stores', 'store_inventory_details.store_id', '=', 'stores.id')
        ->where('product_id', $id)
        ->select(
            'store_inventory_details.*',
            'stores.store_name as store_name' // Select the store name
        )
        ->orderBy('store_inventory_details.updated_at', 'desc')
        ->paginate(10);

        return view('pages.product.product.store_stock', $data);
    }

    // Warehouse Details Stock
    public function warehouse_stock($id)
    {
        $data = $common_data = $this->product_overview($id);
        $data['warehouse_details'] = WarehouseInventoryDetail::leftJoin('warehouses', 'warehouse_inventory_details.warehouse_id', '=', 'warehouses.id')
        ->where('product_id', $id)
        ->select(
            'warehouse_inventory_details.*',
            'warehouses.name as warehouse_name' // Select the store name
        )
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        return view('pages.product.product.warehouse_details', $data);
    }
}
