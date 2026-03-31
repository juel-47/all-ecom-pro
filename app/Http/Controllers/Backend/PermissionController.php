<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PermissionDataTable;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('backend.authorization.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.authorization.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|unique:permissions,name',
        ];

        $validatedData = $request->validate($rules);

        $user = new Permission();
        $user->name = $validatedData['name'];
        $user->guard_name  = 'web';
        $user->save();

        Toastr::success('Permission created successfully', 'Success');
        return redirect()->route('admin.permission.index');
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
        $permission = Permission::findOrFail($id);
        return view('backend.authorization.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required|string|unique:permissions,name,' . $id,
        ];

        $validatedData = $request->validate($rules);

        $user = Permission::findOrFail($id);
        $user->name = $validatedData['name'];
        $user->guard_name  = 'web';
        $user->save();

        Toastr::success('Permission updated successfully', 'Success');
        return redirect()->route('admin.permission.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Permission::findOrFail($id);
        $user->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
}
