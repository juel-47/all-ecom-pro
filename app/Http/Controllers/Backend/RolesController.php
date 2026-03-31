<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\RolesDataTable;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('backend.authorization.role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('backend.authorization.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            // 'name' => 'required|string',
            'name' => 'required|string|max:255|not_in:super_admin',
        ];
        $messages = [
            'name.not_in' => 'You cannot create super_admin role',
        ];

        $validatedData = $request->validate($rules, $messages);

        $user = new Role();
        $user->name = $validatedData['name'];
        $user->save();
        if ($request->permissions) {
            foreach ($request->permissions as $permission) {
                // return $permission;
                DB::insert('INSERT INTO role_has_permissions (role_id, permission_id) VALUES (?, ?)', [$user->id, $permission]);
            }
        }
        Toastr::success('Role created successfully');
        // Redirect or return a response as needed
        return redirect()->route('admin.role.index');
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
        $role = Role::find($id);
        // Protect SuperAdmin
        // if ($role->id == 1 || $role->name === 'SuperAdmin') {
        //     abort(403, 'You are not allowed to edit this role.');
        // }
        $permissions = Permission::get();
        $roleHasPermissions = DB::table('role_has_permissions')
            ->where('role_id', $id)
            ->get();
        return view('backend.authorization.role.edit', compact('role', 'permissions', 'roleHasPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        // if ($role->id == 1 || $role->name === 'SuperAdmin') {
        //     abort(403, 'You are not allowed to update this role.');
        // }

        $rules = [
            'name' => 'nullable|string',
        ];

        $validatedData = $request->validate($rules);

        $role->name = $validatedData['name'];
        // $role->save();
        // if ($request->permissions) {
        //     // Sync permissions for the role
        //     $role->permissions()->sync($request->permissions);
        // }
        $role->permissions()->sync($request->permissions ?? []);
        $role->save();
        Toastr::success('Role updated successfully');
        // Redirect or return a response as needed
        return redirect()->route('admin.role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        // Protect SuperAdmin
        if ($role->id == 1 || strtolower($role->name) === 'superadmin') {
            return response()->json([
                'message' => 'SuperAdmin role cannot be deleted.'
            ], 403);
        }
        $role->users()->detach();
        $role->permissions()->detach();

        // Delete the role
        $role->delete();

        return response(['status' => 'success', 'message' => 'Role Deleted Successfully!']);
    }
}
