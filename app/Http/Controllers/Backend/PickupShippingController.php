<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PickupShippingDataTable;
use App\Http\Controllers\Controller;
use App\Models\PickupShippingMethod;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PickupShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PickupShippingDataTable $dataTable)
    {
        return $dataTable->render('backend.pickup_shipping.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pickup_shipping.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'address' => 'required|string',
            'map_location' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'cost' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['cost'] = $request->cost ?? 0;

        PickupShippingMethod::create($data);
        Toastr::success('Pickup Store created.');
        return redirect()->route('admin.pickup-shipping.index');
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
        $pickup_shipping = PickupShippingMethod::findOrFail($id);
        return view('backend.pickup_shipping.edit', compact('pickup_shipping'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $store = PickupShippingMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'address' => 'required|string',
            'map_location' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'cost' => 'nullable|numeric',
        ]);

        // $store->update($request->all());
         $data = $request->all();
        $data['cost'] = $request->cost ?? 0;

        $store->update($data);

        Toastr::success('Pickup Store updated.');
        return redirect()->route('admin.pickup-shipping.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $store = PickupShippingMethod::findOrFail($id);
        $store->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }
}
