<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\DataTables\OrderStatusDataTable;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrderStatusDataTable $dataTable)
    {
        return $dataTable->render('backend.orders.order_status.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.orders.order_status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:200|unique:order_statuses,name',
            'status' => 'required|boolean',
        ]);
        $order_status = new OrderStatus();
        $order_status->name = $request->name;
        $order_status->slug = Str::slug($request->name);
        $order_status->status = $request->status;
        $order_status->save();
        Toastr::success('Order Status Created Successfully!');
        return redirect()->route('admin.order-status.index');
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
        $order_status = OrderStatus::findOrFail($id);
        return view('backend.orders.order_status.edit', compact('order_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:200|unique:order_statuses,name,' . $id,
            'status' => 'required|boolean',
        ]);
        $order_status = OrderStatus::findOrFail($id);
        $order_status->name = $request->name;
        $order_status->slug = Str::slug($request->name);
        $order_status->status = $request->status;
        $order_status->save();
        Toastr::success('Order Status Updated Successfully!');
        return redirect()->route('admin.order-status.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order_status = OrderStatus::findOrFail($id);
        $order_status->delete();
        return response(['status' => 'success', 'message' => 'Order Status Deleted Successfully!']);
    }
    /**
     * change status
     */
    public function changeStatus(Request $request)
    {
        $order_status = OrderStatus::findOrFail($request->id);
        $order_status->status = $request->status == 'true' ? 1 : 0;
        $order_status->save();
        session()->flash('success', 'Order Status Updated Successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Order Status Updated Successfully!'
        ]);
        // return response(['status' => 'success', 'message' => 'Order Status Updated Successfully!']);
    }
}
