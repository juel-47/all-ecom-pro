<?php

use App\Models\GeneralSetting;
use illuminate\Support\Str;


/**
 * payoneer credentials
 */
if (!function_exists('payoneer_config')) {
    function payoneer_config()
    {
        $config = config('payoneer.settings');
        return $config();
    }
}

/**
 *  set sidebar Active
 */
function setActive(array $routes, string $className = 'active')
{
    if (is_array($routes)) {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return $className;
            }
        }
    }
    return '';
}
/**
 * check if product have discount
 */

function checkDiscount($product)
{
    $currentDate = date("Y-m-d");
    if ($product->offer_price > 0 && $currentDate >= $product->offer_start_date && $currentDate <= $product->offer_end_date) {
        return true;
    }
    return false;
}
/**
 * calculate discount percent
 */
function calculateDiscountPercent($originalPrice, $discountPrice)
{
    $discountAmount = ($originalPrice - $discountPrice);
    $discountPercent = ($discountAmount / $originalPrice) * 100;
    return round($discountPercent);
}

/**
 * check the type of product
 */
function productType($type)
{
    switch ($type) {
        case 'new_arrival':
            return 'New';
            break;
        case 'featured_product':
            return 'Featured';
            break;
        case 'top_product':
            return 'Top';
            break;
        case 'best_product':
            return 'Best';
            break;

        default:
            return '';
            break;
    }
}
/**
 * limit text for product name
 */
function limitText($text, $limit = 20)
{
    return Str::limit($text, $limit);
}

/** get currency icon */
function getCurrencyIcon()
{
    $icon = GeneralSetting::first();
    return $icon->currency_icon;
}
/**
 * api response
 */
function apiResponse($status, $message = '', $data = [], $code = 200)
{
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ], $code);
}
