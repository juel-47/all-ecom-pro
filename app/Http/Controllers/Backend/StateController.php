<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\StateDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StateDataTable $dataTable)
    {
        return $dataTable->render('backend.shipping.states.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries  = Country::all();
        return view('backend.shipping.states.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'country_id' => 'required|exists:countries,id',
            'status' => 'required|boolean'
        ]);
        State::create([
            'country_id' => $request->country_id,
            'name' => $request->name,
            'status' => $request->status
        ]);
        Toastr::success('State added successfully!');
        return redirect()->route('admin.states.index');
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
        $state = State::findOrFail($id);
        return view('backend.shipping.states.edit', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'country_id' => 'required|exists:countries,id',
            'status' => 'required|boolean'
        ]);
        $state = State::findOrFail($id);
        $state->update([
            'country_id' => $request->country_id,
            'name' => $request->name,
            'status' => $request->status
        ]);
        Toastr::success('State updated successfully!');
        return redirect()->route('admin.states.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $state = State::findOrFail($id);
        $state->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }
    function changeStatus(Request $request)
    {
        $coupon = State::findOrFail($request->id);
        $coupon->status = $request->status == 'true' ? 1 : 0;
        $coupon->save();
        return response(['message' => 'State Status has been Updated!']);
    }
    public function getStates(Country $country)
    {
        return response($country->states()->get());
    }
}
