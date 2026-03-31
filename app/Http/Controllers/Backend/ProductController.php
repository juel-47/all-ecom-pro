<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OutOfStockDataTable;
use App\DataTables\ProductDataTable;
use App\DataTables\ProductsPendingDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Color;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImageGallery;
use App\Models\Size;
use App\Models\SubCategory;
use App\Traits\ImageUploadTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('backend.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();
        $colors = Color::where('status', 1)->get();
        $sizes = Size::where('status', 1)->get();
        return view('backend.product.create', compact('categories', 'brands', 'colors', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */

// public function store(ProductCreateRequest $request)
// {
//     /** ensure array exists */
//     $proColors = $request->proColor ?? [];
//     $colorImages = $request->file('color_image') ?? [];

//     /** COLOR + IMAGE VALIDATION */
//     foreach ($proColors as $index => $color_id) {

//         $imageFile = $colorImages[$index] ?? null;

//         // color selected but no image
//         if ($color_id && !$imageFile) {
//             Toastr::error("Color image is required when a color is selected!", "Error");
//             return back()->withInput();
//         }

//         // image uploaded but no color
//         if (!$color_id && $imageFile) {
//             Toastr::error("Please select a color for the uploaded image!", "Error");
//             return back()->withInput();
//         }
//     }

//     /** PRODUCT CREATE */
//     $lastId = (Product::max('id') ?? 0) + 1;
//     $productCode = 'P' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

//     $imagePath = $this->upload_image($request, 'image', 'uploads/products');

//     $product = new Product();
//     $product->thumb_image = $imagePath;
//     $product->name = $request->name;
//     $product->slug = Str::slug($request->name);
//     $product->category_id = $request->category;
//     $product->sub_category_id = $request->sub_category;
//     $product->child_category_id = $request->child_category;
//     $product->brand_id = $request->brand;
//     $product->qty = $request->qty;
//     $product->short_description = $request->short_description;
//     $product->long_description = $request->long_description;
//     $product->video_link = $request->video_link;
//     $product->sku = $request->sku;
//     $product->purchase_price = $request->purchase_price ?? null;
//     $product->price = $request->price;
//     $product->offer_price = $request->offer_price;
//     $product->offer_start_date = $request->offer_start_date;
//     $product->offer_end_date = $request->offer_end_date;
//     $product->product_type = $request->product_type;
//     $product->status = $request->status;
//     $product->meta_title = $request->meta_title;
//     $product->meta_description = $request->meta_description;
//     $product->created_by = auth()->id();
//     $product->product_code = $productCode;
//     $product->is_approved = auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin') ? 1 : 0;
//     $product->save();


//     /** COLOR IMAGE UPLOAD (SAFE FIXED VERSION) */
//     foreach ($proColors as $index => $color_id) {

//         if (!$color_id) continue;

//         $imageFile = $colorImages[$index] ?? null;
//         if (!$imageFile) continue;

//         // ensure folder exists
//         $path = public_path('uploads/image-gallery');
//         if (!file_exists($path)) {
//             mkdir($path, 0777, true);
//         }

//         $ext = $imageFile->getClientOriginalExtension();
//         $imageName = 'media_' . uniqid() . '.' . $ext;
//         $imageFile->move($path, $imageName);

//         ProductImageGallery::create([
//             'product_id' => $product->id,
//             'color_id'   => $color_id,
//             'image'      => 'uploads/image-gallery/' . $imageName,
//         ]);
//     }




//     Toastr::success('Created Product Successfully!', 'success');
//     return redirect()->route('admin.products.index');
// }
public function store(ProductCreateRequest $request)
{
    /** ensure array exists */
    $proColors = $request->proColor ?? [];
    $colorImages = $request->file('color_image') ?? [];

    /** COLOR + IMAGE VALIDATION */
    foreach ($proColors as $index => $color_id) {
        $imageFile = $colorImages[$index] ?? null;

        // color selected but no image
        if ($color_id && !$imageFile) {
            Toastr::error("Color image is required when a color is selected!", "Error");
            return back()->withInput();
        }

        // image uploaded but no color
        if (!$color_id && $imageFile) {
            Toastr::error("Please select a color for the uploaded image!", "Error");
            return back()->withInput();
        }
    }

    /** PRODUCT CREATE */
    $lastId = (Product::max('id') ?? 0) + 1;
    $productCode = 'P' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

    $imagePath = $this->upload_image($request, 'image', 'uploads/products');

    $product = new Product();
    $product->thumb_image = $imagePath;
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->category_id = $request->category;
    $product->sub_category_id = $request->sub_category;
    $product->child_category_id = $request->child_category;
    $product->brand_id = $request->brand;
    $product->qty = $request->qty;
    $product->short_description = $request->short_description;
    $product->long_description = $request->long_description;
    $product->video_link = $request->video_link;
    $product->sku = $request->sku;
    $product->purchase_price = $request->purchase_price ?? null;
    $product->price = $request->price;
    $product->offer_price = $request->offer_price;
    $product->offer_start_date = $request->offer_start_date;
    $product->offer_end_date = $request->offer_end_date;
    $product->product_type = $request->product_type;
    $product->status = $request->status;
    $product->meta_title = $request->meta_title;
    $product->meta_description = $request->meta_description;
    $product->created_by = auth()->id();
    $product->product_code = $productCode;
    $product->is_approved = auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin') ? 1 : 0;
    $product->save();

    $sizesToSync  = [];
    $colorsToSync = [];

    if ($request->filled('variants') && is_array($request->variants)) {
        foreach ($request->variants as $variant) {
            // Size 
            if (!empty($variant['size_id'])) {
                $sizesToSync[$variant['size_id']] = [
                    'size_price' => $variant['price'] ?? 0,
                ];
            }

            // Color 
            if (!empty($variant['color_id'])) {
                $colorsToSync[$variant['color_id']] = [
                    'color_price' => $variant['price'] ?? 0,
                ];
            }
        }

        // Sync sizes and colors
        $product->sizes()->sync($sizesToSync);
        $product->colors()->sync($colorsToSync);
    }
    // ======================================================================

    /** COLOR IMAGE UPLOAD */
    foreach ($proColors as $index => $color_id) {
        if (!$color_id) continue;

        $imageFile = $colorImages[$index] ?? null;
        if (!$imageFile) continue;

        $path = public_path('uploads/image-gallery');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $ext = $imageFile->getClientOriginalExtension();
        $imageName = 'media_' . uniqid() . '.' . $ext;
        $imageFile->move($path, $imageName);

        ProductImageGallery::create([
            'product_id' => $product->id,
            'color_id'   => $color_id,
            'image'      => 'uploads/image-gallery/' . $imageName,
        ]);
    }



    Toastr::success('Created Product Successfully!', 'success');
    return redirect()->route('admin.products.index');
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
        $product = Product::with(['productImageGalleries'])->findOr($id);
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $childCategories = ChildCategory::where('sub_category_id', $product->sub_category_id)->get();
        $categories = Category::where('status', 1)->get();
        $brands = Brand::all();
        $colors = Color::where('status', 1)->get();
        $sizes = Size::where('status', 1)->get();
        return view('backend.product.edit', compact('product', 'categories', 'brands', 'subCategories', 'childCategories', 'colors', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        // dd($request->all());
        $product = Product::find($id);

        /** handle image update*/
        $imagePath = $this->update_image($request, 'image', 'uploads/products', $product->thumb_image);
        $product->thumb_image = empty(!$imagePath) ? $imagePath : $product->thumb_image;

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category;
        $product->child_category_id = $request->child_category;
        $product->brand_id = $request->brand;
        $product->qty = $request->qty;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->video_link = $request->video_link;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->offer_start_date = $request->offer_start_date;
        $product->offer_end_date = $request->offer_end_date;
        $product->product_type = $request->product_type;
        $product->status = $request->status;
        if (auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')) {
            $product->is_approved = 1;
        } else {
            if ($product->is_approved == 1) {
                $product->is_approved = 1;
            } else {
                $product->is_approved = 0;
            }
        }
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->save();

        /** Handle Size + Color Variants */
        $sizesToSync = [];
        $colorsToSync = [];

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if (!empty($variant['size_id'])) {
                    $sizesToSync[$variant['size_id']] = ['size_price' => $variant['price'] ?? 0];
                }
                if (!empty($variant['color_id'])) {
                    $colorsToSync[$variant['color_id']] = ['color_price' => $variant['price'] ?? 0];
                }
            }
        }
        // dd($sizesToSync, $colorsToSync);

        // Sync: Merge old + new
        $product->sizes()->sync($sizesToSync);
        $product->colors()->sync($colorsToSync);

        // Remove old galleries if needed or update
    /** Handle Deleted Colors */
    if ($request->has('deleted_color_ids') && !empty($request->deleted_color_ids)) {
        $deletedIds = explode(',', $request->deleted_color_ids);
        ProductImageGallery::where('product_id', $product->id)
            ->whereIn('color_id', $deletedIds)
            ->delete();
    }

    /** Handle Color + Color Images */
    // if ($request->has('proColor')) {
    //     foreach ($request->proColor as $index => $color_id) {
    //         if (!$color_id) continue;

    //         $existingGallery = $product->productImageGalleries[$index] ?? null;
    //         $imagePathColor = null;

    //         if ($request->hasFile('color_image') && isset($request->file('color_image')[$index])) {
    //             $singleFileRequest = new \Illuminate\Http\Request();
    //             $singleFileRequest->files->set('color_image', $request->file('color_image')[$index]);
    //             $imagePathColor = $this->updateImage(
    //                 $singleFileRequest,
    //                 'color_image',
    //                 'uploads/image-gallery',
    //                 $existingGallery->image ?? null,u
    //                 400,
    //                 500
    //             );
    //         } else {
    //             $imagePathColor = $existingGallery->image ?? null;
    //         }

    //         ProductImageGallery::updateOrCreate(
    //             ['product_id' => $product->id, 'color_id' => $color_id],
    //             ['image' => $imagePathColor]
    //         );
    //     }
    // }
    /** extra validtion for color and image */
        $proColors = $request->proColor ?? [];
        $colorImages = $request->file('color_image') ?? [];

        // Fetch old galleries for existing colors
        $oldGalleries = $product->productImageGalleries()->get()->keyBy('color_id');

        foreach ($proColors as $index => $color_id) {
            // if (!$color_id) continue;

            // $hasOldImage = isset($oldGalleries[$color_id]);
            // $hasNewImage = !empty($colorImages[$index]);

            // if (!$hasOldImage && !$hasNewImage) {
            //     Toastr::error("Color image is required when a new color is selected!", "Error");
            //     return back()->withInput();
            // }
            $hasColor = !empty($color_id);
    $hasImage = isset($colorImages[$index]) && !empty($colorImages[$index]);

            $hasOldImage = isset($oldGalleries[$color_id]);
        
            /** CASE 1: Color selected but no image provided */
            if ($hasColor && !$hasImage && !$hasOldImage) {
                Toastr::error("You selected a color but did not upload an image!", "Error");
                return back()->withInput();
            }
        
            /** CASE 2: Image provided but no color selected */
            if (!$hasColor && $hasImage) {
                Toastr::error("You uploaded an image but did not select a color!", "Error");
                return back()->withInput();
            }
        }
    /** Handle Color + Color Images */
if ($request->has('proColor')) {
    foreach ($request->proColor as $index => $color_id) {
        if (!$color_id) continue;

        // Existing gallery check
        $existingGallery = $product->productImageGalleries()->where('color_id', $color_id)->first();
        $imagePathColor = null;

        if ($request->hasFile('color_image') && isset($request->file('color_image')[$index])) {
            $singleFileRequest = new \Illuminate\Http\Request();
            $singleFileRequest->files->set('color_image', $request->file('color_image')[$index]);
            // $imagePathColor = $this->updateImage(
            //     $singleFileRequest,
            //     'color_image',
            //     'uploads/image-gallery',
            //     $existingGallery->image ?? null,
            //     400,
            //     500
            // );
             $imagePathColor = $this->update_image(
                $singleFileRequest,
                'color_image',
                'uploads/image-gallery',
                $existingGallery->image ?? null
            );
        }

        if ($imagePathColor || $existingGallery) {
            ProductImageGallery::updateOrCreate(
                ['product_id' => $product->id, 'color_id' => $color_id],
                ['image' => $imagePathColor ?? $existingGallery->image]
            );
        }
    }
}





        Toastr::success('Product Updated Successfully!', 'success');
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if (OrderProduct::where('product_id', $product->id)->count() > 0) {
            return response(['status' => 'error', 'message' => 'Product have orders! Can not be deleted']);
        }

        // Detach sizes and colors from pivot tables
        $product->sizes()->detach();
        $product->colors()->detach();

        // /**Delete main product image */
        $this->deleteImage($product->thumb_image);

        // /** delete product gallery images */
        $galleryImages = ProductImageGallery::where('product_id', $product->id)->get();
        foreach ($galleryImages as $image) {
            $this->deleteImage($image->image);
            $image->delete();
        }

        $product->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
    /**
     * Get all sub categories
     */
    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->id)->get();
        return $subCategories;
    }
    /**
     * Get all child categories
     */
    public function getChildCategories(Request $request)
    {
        $childCategories = ChildCategory::where('sub_category_id', $request->id)->get();
        return $childCategories;
    }
    /**
     * Change product status
     */
    function changeStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = $request->status == 'true' ? 1 : 0;
        $product->save();
        return response(['message' => 'Status has been Updated!']);
    }
    /**
     * product pending 
     */
    public function productsPending(ProductsPendingDataTable $dataTable)
    {
        return $dataTable->render('backend.product.pending.index');
    }
    /** change product approve status */
    public function changeApproveStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->is_approved = $request->value;
        $product->save();
        return response(['message' => 'Product Approved Status Has Been Changed!']);
    }

    public function outOfStock(OutOfStockDataTable $dataTable)
    {
        return $dataTable->render('backend.product.outofstock.index');
    }
}