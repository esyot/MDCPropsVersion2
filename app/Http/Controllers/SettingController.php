<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Auth;

class SettingController extends Controller
{
    public function darkMode(Request $request, $id)
    {

        $setting = Setting::find($id);

        if ($request->action == null) {

            $setting->update([
                'darkMode' => false
            ]);

        } else {

            $setting->update([
                'darkMode' => true
            ]);
        }


        return redirect()->back();
    }
    public function transitions(Request $request, $id)
    {

        $setting = Setting::find($id);

        if ($request->action == null) {

            $setting->update([
                'transition' => false
            ]);

        } else {

            $setting->update([
                'transition' => true
            ]);
        }


        return redirect()->back();
    }
}
