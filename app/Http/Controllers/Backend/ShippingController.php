<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ShippingMethodDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use App\Models\ShippingMethod;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index(ShippingMethodDataTable $dataTable)
    {
        // $methods = ShippingMethod::with('charges.country', 'charges.state')->get();
        // return view('admin.shipping.index', compact('methods'));
        return $dataTable->render('backend.shipping.index');
    }

    public function create()
    {
        return view('backend.shipping.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|array',
            'type.*' => 'required|string',
            'status' => 'required|boolean'
        ]);

        ShippingMethod::create([
            'name' => $request->name,
            'type' => json_encode($request->type),
            'status' => $request->status
        ]);

        Toastr::success('Shipping Method Created Successfully!');
        return back();
    }

    public function edit(string $id)
    {
        $shipping = ShippingMethod::findOrFail($id);
        return view('backend.shipping.edit', compact('shipping'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|array',
            'type.*' => 'required|string',
            'status' => 'required|boolean'
        ]);

        $shipping = ShippingMethod::findOrFail($id);
        $shipping->update([
            'name' => $request->name,
            'type' => json_encode($request->type),
            'status' => $request->status,
        ]);

        Toastr::success('Shipping Method Updated Successfully!');
        return redirect()->route('admin.shipping-methods.index');
    }

    public function destroy(ShippingMethod $shipping)
    {
        $shipping->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $shipping_method = ShippingMethod::findOrFail($request->id);
        $shipping_method->status = $request->status == 'true' ? 1 : 0;
        $shipping_method->save();
        return response(['status' => 'success', 'message' => 'Status Change Successfully!']);
    }
    
    public function chargeForm(ShippingMethod $shipping)
    {
        $countries = Country::with('states')->get();
        $charges = $shipping->charges()->with('country', 'state')->get();
        return view('backend.shipping.charge', compact('shipping', 'countries', 'charges'));
    }
    public function saveCharge(Request $request, ShippingMethod $shipping)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'base_charge' => 'required|numeric',
            'min_weight' => 'required|numeric',
            'max_weight' => 'required|numeric',
        ]);

        ShippingCharge::updateOrCreate(
            [
                'shipping_method_id' => $shipping->id,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'min_weight' => $request->min_weight,
                'max_weight' => $request->max_weight
            ],
            [
                'base_charge' => $request->base_charge,
                'extra_per_kg' => $request->extra_per_kg
            ]
        );

        return back()->with('success', 'Charge Saved Successfully!');
    }
    public function deleteCharge(ShippingCharge $charge)
    {
        $charge->delete();
        Toastr::success('Charge Deleted Successfully!');
        return back();
    }
}
