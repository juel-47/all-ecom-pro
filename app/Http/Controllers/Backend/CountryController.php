<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CountryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CountryDataTable $dataTable)
    {
        return $dataTable->render('backend.shipping.country.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.shipping.country.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|max:50',
            'status' => 'required|boolean'
        ]);
        $code = strtoupper($request->code);
        Country::create([
            'name' => $request->name,
            'code' => $code,
            'status' => $request->status

        ]);
        Toastr::success('Country added successfully!');
        return redirect()->route('admin.countries.index');
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
        $country = Country::findOrFail($id);
        return view('backend.shipping.country.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|max:50',
            'status' => 'required|boolean'
        ]);
        $code = strtoupper($request->code);
        $country = Country::findOrFail($id);
        $country->update([
            'name' => $request->name,
            'code' => $code,
            'status' => $request->status
        ]);
        Toastr::success('Country updated successfully!');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $country = Country::findOrFail($id);
        $country->states()->delete();
        $country->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }
    function changeStatus(Request $request)
    {
        $country = Country::findOrFail($request->id);
        $country->status = $request->status == 'true' ? 1 : 0;
        $country->save();
        return response(['message' => 'Country Status has been Updated!']);
    }
    public function getCountries()
    {
        $countries = Country::where('status', 1)->select('id', 'name')->orderBy('name')->get();
        return response()->json($countries);
    }
}
