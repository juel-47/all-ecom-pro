<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SizeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\SizeCreateRequest;
use App\Http\Requests\SizeUpdateRequest;
use App\Models\Size;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SizeDataTable $dataTable)
    {
        return $dataTable->render('backend.size.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.size.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SizeCreateRequest $request)
    {
        $validated = $request->validated();
        $validated['price'] = $request->price ?? 0;
        $this->handleDefaultColor(null, $request->is_default);
        Size::create($validated);
        Toastr::success('Size Added Successfully', 'success');
        return redirect()->route('admin.size.index');
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
        $size = Size::findOrFail($id);
        return view('backend.size.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SizeUpdateRequest $request, string $id)
    {
        $validated=$request->validated();
        $size = Size::findOrFail($id);
        $validated['price'] = $request->price ?? 0;
        $this->handleDefaultColor($size, $request->is_default);
        $size->update($validated);
        Toastr::success('Size Updated Successfully', 'success');
        return redirect()->route('admin.size.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $size = Size::findOrFail($id);
        $size->delete();
        Toastr::success('Size Deleted Successfully', 'success');
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
    /**
     * change status
     */
    public function changeStatus(Request $request)
    {
        $size = Size::findOrFail($request->id);
        $size->status = $request->status == 'true' ? 1 : 0;
        $size->save();
        return response(['status' => 'success', 'message' => 'Status Changed Successfully!']);
    }
        /**
     * Handle default size logic
     * @param Size|null $size : For update, pass existing size. For create, pass null.
     * @param int $isDefault
     */
    protected function handleDefaultColor(?Size $color, $isDefault)
    {
        if ($isDefault == 1) {
            $query = Size::where('is_default', 1);
            if ($color) {
                $query->where('id', '!=', $color->id);
            }
            $query->update(['is_default' => 0]);
        }
    }
}
