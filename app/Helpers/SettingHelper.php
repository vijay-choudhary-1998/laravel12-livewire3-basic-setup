<?php

namespace App\Helpers;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SettingHelper
{
    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", now()->addHours(1), function () use ($key) {
            return SiteSetting::where('key', $key)->value('value');
        }) ?? $default;
    }
}