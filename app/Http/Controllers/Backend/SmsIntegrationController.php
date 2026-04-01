<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class SmsIntegrationController extends Controller
{
    public function index()
    {
        return view('backend.integrations.sms');
    }
}
