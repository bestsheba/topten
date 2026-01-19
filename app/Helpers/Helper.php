<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * flash message
 *
 * @param  string  $message
 * @param  type  $type
 * @return void
 */
if (! function_exists('flashMessage')) {
    function flashMessage(?string $type, string $message)
    {
        session()->flash($type, $message);
    }
}

/**
 * show amount with currency symbol
 *
 * @param  string  $amount
 * @param  string  $symbol_position  | 1 left , 2 right
 * @param  string  $symbol_type  | 1 ৳ , 2 BDT
 * @param  string  $rounded | decimal point
 * @return string
 */
if (! function_exists('currencySymbol')) {
    function currencySymbol()
    {
        return "৳";
    }
}

if (! function_exists('currencyCode')) {
    function currencyCode()
    {
        return "BDT";
    }
}
if (! function_exists('showAmount')) {
    function showAmount(?string $amount, string $symbol_position = '1', string $rounded = '2', string $symbol_type = '1')
    {
        $symbol = "৳";
        $sort_code = "BDT";

        // Ensure $amount is numeric, otherwise return "0.00" as default
        $amount = is_numeric($amount) ? floatval($amount) : 0;

        // Determine decimal places
        $rounded = is_numeric($rounded) ? intval($rounded) : 2;

        // Format amount with appropriate symbol or sort code
        if ($symbol_type === '1') {
            if ($symbol_position === '1') {
                return $symbol . number_format($amount, $rounded);
            } else {
                return number_format($amount, $rounded) . $symbol;
            }
        } else {
            if ($symbol_position === '1') {
                return $sort_code . ' ' . number_format($amount, $rounded);
            } else {
                return number_format($amount, $rounded) . ' ' . $sort_code;
            }
        }
    }
}


if (! function_exists('formatTime')) {

    function formatTime($date, $format = 'F d, Y H:i A')
    {
        if (! $date) {
            return '';
        }

        return Carbon::parse($date)->format($format);
    }
}
function calculateDiscountedPrice(float $price, ?string $discountType, float $discountValue): float
{
    switch ($discountType) {
        case 'percentage':
            return $price - ($price * $discountValue / 100);
        case 'amount':
            return max($price - $discountValue, 0);
        default:
            return $price;
    }
}

function makeAvatar($name)
{
    $name = trim(collect(explode(' ', $name))->map(function ($segment) {
        return mb_substr($segment, 0, 1);
    })->join(' '));

    return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
}

function isImageUrl($url)
{
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];

    $path = parse_url($url, PHP_URL_PATH);
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    return in_array($extension, $imageExtensions);
}

if (!function_exists('userCan')) {
    function userCan($permission)
    {
        $user = auth('admin')->user();
        return $user && method_exists($user, 'can') && $user->can($permission);
    }
}
