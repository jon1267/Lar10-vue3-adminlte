<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Http\Requests\SettingsUpdateRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        return Setting::pluck('value','key')->toArray();
    }

    public function update(SettingsUpdateRequest $request)
    {
        //$settings = request()->all(); //dd(request()->all());
        $settings = $request->validated();

        foreach ($settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]); //! update(['value'=> $value])
        }

        Cache::forget('settings'); // flush('settings') ???
        return response()->json(['success' => true]);
    }
}
