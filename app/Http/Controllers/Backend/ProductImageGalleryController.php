<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductImageGalleryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageGalleryCreateRequest;
use App\Models\Product;
use App\Models\ProductImageGallery;
use App\Traits\ImageUploadTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ProductImageGalleryController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductImageGalleryDataTable $dataTable, Request $request)
    {
        $product = Product::findOrFail($request->product);
        return $dataTable->render('backend.product.image-gallery.index', compact('product'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductImageGalleryCreateRequest $request)
    {
        // $validation = $request->validate([
        //     'image.*' => 'required|image|max:2048',
        // ]);

        /**handle image upload */
        $imagePaths = $this->upload_multiImage($request, 'image', 'uploads/image-gallery');

        /**save image path to database */
        foreach ($imagePaths as $path) {
            $productImageGallery = new  ProductImageGallery();
            $productImageGallery->image = $path;
            $productImageGallery->product_id = $request->product_id;
            $productImageGallery->save();
        }
        Toastr::success('Uploaded Successfully', 'success');
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = ProductImageGallery::findOrFail($id);
        $this->deleteImage($image->image);
        $image->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
}