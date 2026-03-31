<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerAddressRequest;
use App\Http\Requests\UpdateCustomerAddressRequest;
use App\Models\CustomerAddress;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user via Sanctum
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }

        // Get addresses for the authenticated user
        $addresses = CustomerAddress::where('customer_id', $user->id)
            ->select('id', 'customer_id', 'name', 'email', 'phone', 'address', 'city', 'state', 'country', 'zip')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $addresses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerAddressRequest $request)
    {
        // Get authenticated user via Sanctum
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }

        // Attach customer_id to validated data
        $data = $request->validated();
        $data['customer_id'] = $user->id;

        // Create the address
        $address = CustomerAddress::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Address added successfully',
            'data'    => $address
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerAddressRequest $request, string $id)
    {
        // Authenticated user via Sanctum
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }

        // Find the address by id
        $address = CustomerAddress::findOrFail($id);
        // dd($address);
        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found.'
            ], 404);
        }

        // Ensure the authenticated user owns this address
        if ($address->customer_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Update the address using validated data
        $address->update($request->validated());

        // Refresh the model to make sure latest attributes are loaded
        $address->refresh();

        return response()->json([
            'status' => 'success',
            'message' => 'Address updated successfully',
            'data' => $address
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }

        $address = CustomerAddress::find($id);

        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found.'
            ], 404);
        }

        if ($address->customer_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted successfully'
        ]);
    }
}