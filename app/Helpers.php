<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

function setting($key)
{
    $settings = Cache::rememberForever('settings', function () {
        return Setting::pluck('value','key')->all();
    }); //$settings = Setting::pluck('value','key')->all();

    return $settings[$key] ?? false;
}
