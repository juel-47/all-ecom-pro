<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SteadfastSetting;

class ApiIntegrationController extends Controller
{
    public function index()
    {
        $steadfastSetting = SteadfastSetting::first();
        return view('backend.api-integration.index', compact('steadfastSetting'));
    }
}
