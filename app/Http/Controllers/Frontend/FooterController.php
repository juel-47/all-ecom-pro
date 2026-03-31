<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FooterController extends Controller
{
  
    public function supportCenter()
    {
        return inertia('SupportPage');
    }
    public function howToOrder()
    {
        return inertia('HowToOrder');
    }
    public function shippingDelivery()
    {
        return inertia('ShippingInfo');
    }
    public function returnPolicy()
    {
        return inertia('ReturnsPolicy');
    }
    public function privacyPolicy()
    {
        return inertia('PrivacyPolicy');
    }
    public function legalPolicy()
    {
        return inertia('LegalNotice');
    }

}
