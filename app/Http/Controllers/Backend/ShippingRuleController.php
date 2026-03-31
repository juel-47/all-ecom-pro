<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ShippingRuleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingRuleCreateRequest;
use App\Http\Requests\ShippingRuleUpdateRequest;
use App\Models\ShippingRule;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ShippingRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShippingRuleDataTable $dataTable)
    {
        return $dataTable->render('backend.shipping_rule.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.shipping_rule.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShippingRuleCreateRequest $request)
    {
        ShippingRule::create($request->validated());
        Toastr::success('Shipping Rule Added Successfully', 'success');
        return redirect()->route('admin.shipping-rule.index');
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
        $shipping_rule = ShippingRule::findOrFail($id);
        return view('backend.shipping_rule.edit', compact('shipping_rule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShippingRuleUpdateRequest $request, string $id)
    {
        $shipping_rule = ShippingRule::findOrFail($id);
        $shipping_rule->update($request->validated());
        Toastr::success('Shipping Rule Updated Successfully', 'success');
        return redirect()->route('admin.shipping-rule.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shipping_rule = ShippingRule::findOrFail($id);
        $shipping_rule->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }

    /**
     * change status
     */
    public function changeStatus(Request $request)
    {
        $shipping_rule = ShippingRule::findOrFail($request->id);
        $shipping_rule->status = $request->status=='true' ? 1 : 0;
        $shipping_rule->save();
        return response(['status' => 'success', 'message' => 'Status Change Successfully!']);
    }
}
