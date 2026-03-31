<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ChildCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChildCategoryRequest;
use App\Http\Requests\ChildCategoryUpdateRequest;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\SubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ChildCategoryDataTable $dataTable)
    {
        return $dataTable->render('backend.child_category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.child_category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChildCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->name);
        ChildCategory::create($validated);
        Toastr::success('Child Category created successfully!');
        return redirect()->route('admin.child-category.index');
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
        $categories = Category::all();
        $childCategory = ChildCategory::findOrFail($id);
        $subCategories = SubCategory::where('category_id', $childCategory->category_id)->get();
        return view('backend.child_category.edit', compact('categories', 'childCategory', 'subCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChildCategoryUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->name);
        ChildCategory::findOrFail($id)->update($validated);
        Toastr::success('Child Category updated successfully!');
        return redirect()->route('admin.child-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $childCategory = ChildCategory::findOrFail($id);
        if (Product::where('child_category_id', $childCategory->id)->count() > 0) {
            return response(['status' => 'error', 'message' => 'You can\'t delete this category because it has products!']);
        }

        $childCategory->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
    /**
     * get sub categories
     */
    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->id)->where('status', 1)->get();
        return $subCategories;
    }
    /**
     * change status
     */
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $childCategory = ChildCategory::findOrFail($request->id);
        $childCategory->status = $request->status == 'true' ? 1 : 0;
        $childCategory->save();

        return response(['message' => 'Status has been Updated!',]);
    }
}
