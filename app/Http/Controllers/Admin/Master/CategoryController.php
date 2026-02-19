<?php

namespace App\Http\Controllers\Admin\Master;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\Master\CategoryDataTable;
use App\Http\Requests\Master\CategoryFormRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.master.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = Category::select('id', 'name', 'status', 'parent_id')
            ->whereNull('parent_id')
            ->with('getChildrenCategory')
            ->active()
            ->get();

        return view('pages.master.category.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryFormRequest $request)
    {
        // try {
        $slug = CommonComponent::slugCreate($request->name, $request->slug);
        $imagePath = null;
        $imageUrl = null;

        if ($request->hasFile('image')) {
            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'category');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->parent_id = $request->parent_id;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        $category->description = $request->description;
        if ($imageUrl != null) {
            $category->image = $imageUrl;
            $category->image_path = $imagePath;
        }
        $category->status = $request->status;
        $category->is_featured = $request->is_featured;
        $category->save();

        if ($request->submission_type == 1) {
            return redirect()
                ->route('admin.category.index')
                ->with('success', 'Category Store Successfully');
        } elseif ($request->submission_type == 2) {
            return back()->with('success', 'Category Store Successfully');
        }
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Category Stored Fail');
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
        $data['category'] = Category::findOrFail($id);
        $data['categories'] = Category::select('id', 'name', 'status', 'parent_id')
            ->whereNull('parent_id')
            ->with('getChildrenCategory')
            ->active()
            ->get();

        return view('pages.master.category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryFormRequest $request, $id)
    {
        // try {
        $slug = CommonComponent::slugCreate($request->name, $request->slug);

        $imagePath = null;
        $imageUrl = null;

        $category = Category::findOrFail($id);
        if ($request->hasFile('image')) {
            $fileDeleted = CommonComponent::s3BucketFileDelete($category->image, $category->image_path);

            $imageData = CommonComponent::s3BucketFileUpload($request->image, 'category');
            $imagePath = $imageData['filePath'];
            // $imageUrl = $imageData['fileName'];
            $imageUrl = $imageData['imageURL'];
        }

        $category->name = $request->name;
        $category->slug = $slug;
        $category->parent_id = $request->parent_id;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        $category->description = $request->description;
        if ($imageUrl != null) {
            $category->image = $imageUrl;
            $category->image_path = $imagePath;
        }
        $category->status = $request->status;
        $category->is_featured = $request->is_featured;
        $category->save();

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Category Updates Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return back()->withInput()->with('error', 'Category Updated Fail');
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
            $category = Category::findOrFail($id);

            // $fileDeleted = CommonComponent::s3BucketFileDelete($category->image, $category->image_path);

            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category Deleted Successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }

    public function deleteS3Image(Request $request)
    {
        try {
            Log::info("delete image");
            $category = Category::findOrFail($request->id);
            Log::info($category);

            $fileDeleted = CommonComponent::s3BucketFileDelete($category->image, $category->image_path);
            Log::info("filedeleted");
            Log::info($fileDeleted);
            if ($fileDeleted) {
                $categoryData = Category::findOrFail($category->id);
                $categoryData->image = null;
                $categoryData->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Image Deleted Successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Image not found.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 400,
                'message' => 'Sorry, Something went Wrong'
            ]);
        }
    }
}
