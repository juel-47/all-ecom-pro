<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductVariantDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariantCreateRequest;
use App\Http\Requests\ProductVariantUpdateRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductVariantDataTable $dataTable, Request $request)
    {
        $product = Product::findOrFail($request->product);
        return $dataTable->render('backend.product.product-variant.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.product.product-variant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductVariantCreateRequest $request)
    {
        $variant = new ProductVariant();
        $variant->product_id = $request->product_id;
        $variant->name = $request->name;
        $variant->status = $request->status;
        $variant->save();
        Toastr::success('Product Variant Added Successfully', 'success');
        return redirect()->route('admin.product-variant.index', ['product' => $request->product_id]);
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
        $variant = ProductVariant::findOrFail($id);
        return view('backend.product.product-variant.edit', compact('variant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductVariantUpdateRequest $request, string $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $variant->name = $request->name;
        $variant->status = $request->status;
        $variant->save();
        Toastr::success('Product Variant Updated Successfully', 'success');
        return redirect()->route('admin.product-variant.index', ['product' => $variant->product_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variant = ProductVariant::findOrFail($id);
        // $variantItemCheck = ProductVariantItem::where('product_variant_id', $variant->id)->count();
        // if ($variantItemCheck > 0) {
        //     return response(['status' => 'error', 'message' => 'You can not delete this Variant because it has Variant Item! ',]);
        // }
        $variant->delete();

        return response(['status' => 'success', 'message' => 'Delete Successfully!',]);
    }
    /**
     * change status
     */
    public function changeStatus(Request $request)
    {
        $variant = ProductVariant::findOrFail($request->id);
        $variant->status = $request->status == 'true' ? 1 : 0;
        $variant->save();

        return response(['message' => 'Status has been Updated!',]);
    }
}
