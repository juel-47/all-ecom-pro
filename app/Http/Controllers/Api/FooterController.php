<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreatePage;
use App\Models\FooterInfo;
use App\Models\FooterSocial;
use App\Models\LogoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FooterController extends Controller
{
    public function footerInfo()
    {

        // Footer Info Cache
        $footer = Cache::remember('footer_data', 3600, function () {
            return FooterInfo::select('logo', 'phone', 'email', 'address', 'copyright')
                ->get()
                ->map(function ($item) {
                    return $item->only(['logo', 'phone', 'email', 'address', 'copyright']);
                })->first();
        });
        // Footer Social Cache
        $footer_social = Cache::remember('footer_social', 3600, function () {
            return FooterSocial::select('icon', 'icon_extra', 'name', 'url', 'serial_no')
                ->get()
                ->map(function ($item) {
                    return $item->only(['icon', 'icon_extra', 'name', 'url', 'serial_no']);
                });
        });

        // Footer Created Pages Cache
        $footer_create_page = Cache::remember('footer_create_page', 3600, function () {
            return CreatePage::select('page_for', 'name', 'slug', 'title', 'description')
                ->get()
                ->map(function ($item) {
                    return $item->only(['page_for', 'name', 'slug', 'title', 'description']);
                });
        });

        // $logo_fav=LogoSetting::first();
        $logo_fav = Cache::remember('logo_fav', 3600, function () {
            return LogoSetting::first();
        });

        return response()->json([
            'footer_info' => $footer,
            'footer_social' => $footer_social,
            'footer_create_page' => $footer_create_page,
            'logo_fav' => $logo_fav,
        ]);
    }
}
