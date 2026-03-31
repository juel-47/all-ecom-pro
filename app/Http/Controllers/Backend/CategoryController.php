<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CategoriesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCategoryRequest;
use App\Http\Requests\AdminCategoryUpdateRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->render('backend.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminCategoryRequest $request)
    {
        $imagePath = $this->upload_image($request, 'image', 'uploads/categories');
        $validated = $request->validated();
        $validated['icon'] = $request->icon;
        $validated['slug'] = Str::slug($request->name);
        $validated['image'] = $imagePath;
        $validated['front_show'] = $request->front_show == "on" ? 1 : 0;
        $validated['meta_title'] = $request->meta_title;
        $validated['meta_description'] = $request->meta_description;
        Category::create($validated);
        return redirect()->route('admin.category.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        return view('backend.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminCategoryUpdateRequest $request, string $id)
    {
        // dd($request->all());
        $category = Category::findOrFail($id);
        $imagePath = $this->update_image($request, 'image', 'uploads/categories');
        $validated = $request->validated();
        $validated['icon'] = $request->icon;
        $validated['slug'] = Str::slug($request->name);
        // $validated['image'] = !empty($imagePath) ? $imagePath : $category->image;
        if ($imagePath) {
            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = $category->image;
        }
        $validated['front_show'] = $request->front_show == "on" ? 1 : 0;
        $validated['meta_title'] = $request->meta_title;
        $validated['meta_description'] = $request->meta_description;
        $category->update($validated);
        return redirect()->route('admin.category.index')->with('success', 'Update Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $subCategory = SubCategory::where('category_id', $category->id)->count();
        if ($subCategory > 0) {
            return response(['status' => 'error', 'message' => 'You can not delete this category because it has sub category!']);
        }
        $category->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }
    public function changeStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->status = $request->status == 'true' ? 1 : 0;
        $category->save();

        return response(['message' => 'Status has been Updated!',]);
    }
    public function frontShow(Request $request)
    {
        // dd($request->all());
        $category = Category::findOrFail($request->id);
        $category->front_show = $request->status == 'true' ? 1 : 0;
        $category->save();
        return response(['message' => 'Front Show has been Updated!']);
    }
}
