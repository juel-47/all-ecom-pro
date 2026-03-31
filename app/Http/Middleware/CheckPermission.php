<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        $permission = $this->getPermission($request);

        if ($permission && !$user->can($permission)) {
            abort(403, 'Access Denied');
        }
        return $next($request);
    }
    private function getPermission(Request $request)
    {
        $action = $request->route()->getActionName();
        $map = [
            'Manage Categories' => ['CategoryController', 'SubCategoryController', 'ChildCategoryController'],
            'Manage Products' => ['ProductController', 'BrandController', 'SizeController', 'ColorController', 'ReviewController'],
            'Manage Orders' => ['OrderController', 'OrderStatusController'],
            'Manage Ecommerce' => ['CouponController', 'ShippingRuleController', 'FlashSaleController', 'PaymentSettingController', 'PromotionController'],
            'Manage Transaction' => ['TransactionController', 'MobilePaySettingController'],
            'Manage Website' => ['SliderController', 'BranchController', 'CreatePageController', 'PageController', 'OrderStatusController'],
            'Manage Blog' => ['BlogController', 'BlogCategoryController'],
            'Administration' => ['UserController', 'RolesController', 'PermissionController'],
            'Manage Setting & More' => ['FooterInfoController', 'FooterSocialController', 'CustomerListController', 'AdminListController', 'SettingController'],
            'Manage Employee' => ['EmployeeController'],
            'Manage Job Application' => ['JobApplicationController'],
        ];

        foreach ($map as $permission => $controllers) {
            foreach ($controllers as $controller) {
                if (str_contains($action, $controller)) {
                    return $permission;
                }
            }
        }

        return null;
    }
}
