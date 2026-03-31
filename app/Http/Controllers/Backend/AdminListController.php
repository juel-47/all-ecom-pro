<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\AdminListDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminListController extends Controller
{
    /**
     * Show the admin list page.
     *
     * @param AdminListDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(AdminListDataTable $dataTable)
    {
        return $dataTable->render('backend.manage_users.admin_list');
    }
    /**
     * Change the status of an admin.
     *
     * @param Request $request The request object containing the admin ID and the new status.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation with a message.
     */
    public function changeStatus(Request $request)
    {
        $admin = User::findOrFail($request->id);
        $admin->status = $request->status == 'true' ? 1 : 0;
        $admin->save();

        return response(['message' => 'Status has been Updated!',]);
    }
    /**
     * Delete an admin.
     *
     * @param string $id The ID of the admin to delete.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation with a message.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the admin does not exist.
     */
    public function destroy(string $id)
    {
        $admin = User::findOrFail($id);
        $products = Product::where('created_by', $admin->id)->get();
        if (count($products) > 0) {
            return response(['status' => 'error', 'message' => 'Can not delete this user. Because it has products! Please ban this user insted of deleting.']);
        }
        $admin->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
}
