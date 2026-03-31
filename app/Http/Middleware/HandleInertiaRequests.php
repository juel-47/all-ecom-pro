<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\FooterInfo;
use App\Models\FooterSocial;
use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $cartService = new CartService();
        return [
            ...parent::share($request),
            'auth' => [
                'user' => Auth::guard('customer')->user(),
            ],
            'logos' => Cache::remember('shared_logos', 3600, fn() => [
                'logo'    => LogoSetting::value('logo'),
                'favicon' => LogoSetting::value('favicon'),
            ]),
            'cart' => $cartService->getNavbarCartInfo(),
            'footerInfo' => Cache::remember('shared_footer_info', 3600, fn() => 
                FooterInfo::select('logo', 'phone', 'email', 'address', 'copyright')
                    ->first()?->makeHidden([])->toArray() ?? [
                        'logo'       => null,
                        'phone'      => '',
                        'email'      => '',
                        'address'    => '',
                        'copyright'  => '',
                    ]
            ),
            'footer_social' => Cache::remember('shared_footer_social', 3600, fn() => 
                FooterSocial::where('status', 1)->select('icon', 'icon_extra', 'name', 'url', 'serial_no')
                    ->get()
                    ->map(fn($item) => $item->only(['icon', 'icon_extra', 'name', 'url', 'serial_no']))
            ),
            'categoriess' => Cache::remember('shared_categories_tree', 3600, fn() => 
                Category::active()->frontShow()
                    ->with([
                        'subCategories' => function($q) {
                            $q->active()->with(['childCategories' => function($q) {
                                $q->active();
                            }]);
                        }
                    ])->get()
            ),
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
            'settings' => Cache::remember('shared_settings', 3600, fn() => GeneralSetting::first()),
        ];
    }
}
