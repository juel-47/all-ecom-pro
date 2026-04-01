<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SteadfastSetting;

class CourierIntegrationController extends Controller
{
    public function index()
    {
        $steadfastSetting = SteadfastSetting::first();
        return view('backend.integrations.courier', compact('steadfastSetting'));
    }
}
