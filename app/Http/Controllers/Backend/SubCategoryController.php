<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SubCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSubCategoryCreateRequest;
use App\Http\Requests\AdminSubCategoryUpdateRequest;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SubCategoryDataTable $dataTable)
    {
        return $dataTable->render('backend.sub_category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.sub_category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminSubCategoryCreateRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->name);
        SubCategory::create($validated);
        Toastr::success('Sub Category created successfully!');
        return redirect()->route('admin.sub-category.index');
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
        $subCategory = SubCategory::findOrFail($id);
        return view('backend.sub_category.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminSubCategoryUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->name);
        SubCategory::findOrFail($id)->update($validated);
        Toastr::success('Sub Category updated successfully!');
        return redirect()->route('admin.sub-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $childCategory = ChildCategory::where('sub_category_id', $subCategory->id)->count();
        if ($childCategory > 0) {
            return response(['status' => 'error', 'message' => "You can't delete this Sub Category because it has child category!"]);
        }
        $subCategory->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }
    /**
     * Change the status of a subcategory.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStatus(Request $request)
    {
        $subCategory = SubCategory::findOrFail($request->id);
        $subCategory->status = $request->status == 'true' ? 1 : 0;
        $subCategory->save();

        return response(['message' => 'Status has been Updated!',]);
    }
}
