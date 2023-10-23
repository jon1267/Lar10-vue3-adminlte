<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return Setting::pluck('value','key')->toArray();
    }

    public function update()
    {

        $settings =  request()->all();//dd(request()->all());

        foreach ($settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]); //! update(['value'=> $value])
        }

        return response()->json(['success' => true]);
    }
}
