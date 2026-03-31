<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PromotionDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PromotionDataTable $dataTable)
    {
        return $dataTable->render('backend.promotions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $products = Product::all();
        return view('backend.promotions.create', compact('categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:free_shipping,free_product',
            'buy_quantity' => 'required|integer|min:1',
            'get_quantity' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'allow_coupon_stack' => 'required|boolean',
            'status' => 'required|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
        ]);


        $data = $request->only([
            'title',
            'type',
            'category_id',
            'product_id',
            'buy_quantity',
            'get_quantity',
            'allow_coupon_stack',
            'status'
        ]);

        // Handle optional date fields
        $data['start_date'] = $request->start_date ?: null;
        $data['end_date'] = $request->end_date ?: null;

        // Create the promotion
        Promotion::create($data);
        
        Toastr::success('Promotion Created Successfully!');
        return redirect()->route('admin.promotions.index');
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
        $promotion = Promotion::findOrFail($id);
        $categories = Category::all();
        $products = Product::all();
        return view('backend.promotions.edit', compact('promotion', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $promotion = Promotion::findOrFail($id);
        $promotion->update($request->all());
        Toastr::success('Promotion Updated Successfully!');
        return redirect()->route('admin.promotions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return response(['status' => 'success', 'message' => 'Promotion Deleted Successfully!']);
    }
    public function changeStatus(Request $request)
    {
        $promotion = Promotion::findOrFail($request->id);
        $promotion->status = $request->status == 'true' ? 1 : 0;
        $promotion->save();
        return response(['status' => 'success', 'message' => 'Status Change Successfully!']);
    }
}
