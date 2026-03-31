<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as ModelsRole;

class UserController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('backend.authorization.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'web')->get();
        return view('backend.authorization.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'user_role' => 'required',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
            'password' => 'required|min:6',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validatedData = $request->validate($rules);

        $user = new User();
        if ($request->hasFile('image')) {
            $user->image = $this->upload_image(
                $request,
                'image',            // input field name
                'uploads/users'     // folder path
            );
        }

        // Set other fields
        $user->role_id = $validatedData['user_role'];
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->password = bcrypt($validatedData['password']);
        $user->status = $validatedData['status'];

        $user->save();

        // Assign role
        $role = ModelsRole::find($validatedData['user_role']);
        if ($role) {
            $user->assignRole($role);
        }

        Toastr::success('User created successfully', 'Success');

        return redirect()->route('admin.users.index');
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
        $roles = Role::where('guard_name', 'web')->get();
        $user = User::where('id', $id)->first();
        return view('backend.authorization.users.edit', compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'user_role' => 'required',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|unique:users,phone,' . $user->id,
            'status' => 'required|boolean',
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
        ];

        $validatedData = $request->validate($rules);


        if ($request->hasFile('image')) {
            $user->image = $this->update_image(
                $request,
                'image',
                'uploads/users/',
                $user->image
            );
        }

        $user->role_id = $validatedData['user_role'];
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->status = $validatedData['status'];

        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();

        // Sync roles
        $role = Role::find($validatedData['user_role']);
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        Toastr::success('User updated successfully', 'Success');
        return redirect()->route('admin.users.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deletion of super admin (optional)
        if ($user->id == 1) {
            return response()->json(['message' => 'This user cannot be deleted.'], 403);
        }

        // Detach roles and permissions safely
        if ($user->roles()->exists()) {
            $user->roles()->detach();
        }

        if ($user->permissions()->exists()) {
            $user->permissions()->detach();
        }

        // Delete uploaded image if exists
        if ($user->image && file_exists(public_path('uploads/users/' . $user->image))) {
            @unlink(public_path('uploads/users/' . $user->image));
        }

        // Delete the user
        $user->delete();

        return response(['status' => 'success', 'message' => 'User deleted successfully']);
    }

    public function changeStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->status = $request->status == 'true' ? 1 : 0;
        $user->save();
        return response()->json(['message' => 'Status updated successfully']);
    }
}
