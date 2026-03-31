<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BlogCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BlogCategoryDataTable $dataTable)
    {
        return $dataTable->render('backend.blog.blog-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.blog.blog-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|max:200|unique:blog_categories',
            'status' => 'required',
        ], [
            'name.unique' => 'Blog Category Already Exists',
        ]);

        $category = new BlogCategory();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;
        $category->save();
        Toastr::success('Blog Category Created Successfully!');
        return redirect()->route('admin.blog-category.index');
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

        $category = BlogCategory::findOrFail($id);
        return view('backend.blog.blog-category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = $request->validate([
            'name' => 'required|max:200|unique:blog_categories,name,' . $id,
            'status' => 'required',
        ], [
            'name.unique' => 'Blog Category Already Exists',
        ]);

        $blog = BlogCategory::findOrFail($id);
        $blog->name = $request->name;
        $blog->slug = Str::slug($request->name);
        $blog->status = $request->status;
        $blog->save();
        Toastr::success('Blog Category Updated Successfully!');
        return redirect()->route('admin.blog-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog_category = BlogCategory::findOrFail($id);
        $blog_category->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }
    /** change status */
    public function changeStatus(Request $request){
        $blog_category=BlogCategory::findOrFail($request->id);
        $blog_category->status=$request->status == 'true' ? 1 : 0 ;
        $blog_category->save();

        return response(['message'=>'Status has been Updated!', ]);
    }
    /** fronted show status change */
    public function frontShow(Request $request)
    {
        $blog_category = BlogCategory::findOrFail($request->id);
        $blog_category->front_show = $request->front_show == 'true' ? 1 : 0;
        $blog_category->save();
        return response(['message' => 'Front Show has been Updated!']);
    }
}
