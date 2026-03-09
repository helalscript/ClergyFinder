<?php

use App\Models\DynamicPage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;



function generateUniqueSlug($title, $id = null)
{
    // Create an initial slug using Laravel's Str::slug
    $slug = Str::slug($title);
    $original_slug = $slug;

    $count = DynamicPage::where('page_slug', $slug)
        ->when($id, function ($query) use ($id) {
            return $query->where('id', '!=', $id); // Exclude current record if updating
        })
        ->count();

    // If the slug exists, append a number to make it unique
    $index = 1;
    while ($count > 0) {
        $slug = $original_slug . '-' . $index;
        $count = DynamicPage::where('page_slug', $slug)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->count();
        $index++;
    }

    return $slug;
}

function getFileName($file): string
{
    return time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
}
function getEmailName($email): string
{
    // Use explode to split the email into two parts: before and after the '@'
    $parts = explode('@', $email);

    // Return the first part, which is the username
    return $parts[0];
}

function generateUniqueUsername($name): array|string|null
{
    $baseUsername = strtolower($name);
    $username = $baseUsername;
    $count = 1;
    while (User::where('user_name', $username)->exists()) {
        $username = $baseUsername . $count;
        $count++;
    }
    return $username;
}

function formatNumber($number, $precision = 2): array
{
    if ($number >= 1000000000000000) {
        return [
            'number' => number_format($number / 1000000000000000, $precision),
            'format' => 'Q'
        ];
    } elseif ($number >= 1000000000000) {
        return [
            'number' => number_format($number / 1000000000000, $precision),
            'format' => 'T'
        ];
    } elseif ($number >= 1000000000) {
        return [
            'number' => number_format($number / 1000000000, $precision),
            'format' => 'B'
        ];
    } elseif ($number >= 1000000) {
        return [
            'number' => number_format($number / 1000000, $precision),
            'format' => 'M'
        ];
    } elseif ($number >= 1000) {
        return [
            'number' => number_format($number / 1000, $precision),
            'format' => 'K'
        ];
    }

    // For numbers less than 1K, no format suffix is needed
    return [
        'number' => number_format($number),
        'format' => ''
    ];
}

if (!function_exists('is_url')) {
    /**
     * Check if a given string is a valid URL.
     *
     * @param string $url
     * @return bool
     */
    function is_url($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber(): string
    {
        $latestOrderNumber = Order::latest()->first();
        do {
            $orderNumber = 'ORD-' . now()->year . '-' . str_pad(($latestOrderNumber?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT);
            $exists = Order::where('order_number', $orderNumber)->exists();
        } while ($exists);
        return $orderNumber;
    }
}



