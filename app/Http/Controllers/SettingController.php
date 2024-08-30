<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function darkMode(Request $request){

        $setting = Setting::findOrFail(1);
        
        if($request->action == null){

            $setting->update([
                'darkMode'=>false
            ]);

        }else{

            $setting->update([
                'darkMode'=>true
            ]);
        }
        

        return redirect()->back();
    }
    public function transitions(Request $request){

        $setting = Setting::findOrFail(1);
        
        if($request->action == null){

            $setting->update([
                'transition'=>false
            ]);

        }else{

            $setting->update([
                'transition'=>true
            ]);
        }
        

        return redirect()->back();
    }
}
