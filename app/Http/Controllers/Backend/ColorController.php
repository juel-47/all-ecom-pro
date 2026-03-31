<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ColorDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ColorCreateRequest;
use App\Http\Requests\ColorUpdateRequest;
use App\Models\Color;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ColorDataTable $dataTable)
    {
        return $dataTable->render('backend.color.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.color.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColorCreateRequest $request)
    {
        $validated = $request->validated();
        $validated['price'] = $request->price ?? 0;
        $this->handleDefaultColor(null, $request->is_default);
        Color::create($validated);
        Toastr::success('Color Added Successfully', 'success');
        return redirect()->route('admin.color.index');
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
        $color = Color::findOrFail($id);
        return view('backend.color.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColorUpdateRequest $request, string $id)
    {
        $color = Color::findOrFail($id);
        $validated = $request->validated();
        $validated['price'] = $request->price ?? 0;
        $this->handleDefaultColor($color, $request->is_default);
        $color->update($validated);
        Toastr::success('Color  Updated Successfully', 'success');
        return redirect()->route('admin.color.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $color = Color::findOrFail($id);
        $color->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
    /**
     * change status
     */
    public function changeStatus(Request $request)
    {
        $color = Color::findOrFail($request->id);
        $color->status = $request->status == 'true' ? 1 : 0;
        $color->save();

        return response(['message' => 'Status has been Updated!']);
    }

    /**
     * Handle default color logic
     * @param Color|null $color : For update, pass existing color. For create, pass null.
     * @param int $isDefault
     */
    protected function handleDefaultColor(?Color $color, $isDefault)
    {
        if ($isDefault == 1) {
            $query = Color::where('is_default', 1);

            // If updating, exclude the current color
            if ($color) {
                $query->where('id', '!=', $color->id);
            }

            // Set existing default colors to non-default
            $query->update(['is_default' => 0]);
        }
    }
}
