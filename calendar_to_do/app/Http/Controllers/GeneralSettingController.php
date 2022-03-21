<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function show(){
        return GeneralSetting::firstOrNew(['user_id' => \Auth::id()], ['user_id' => \Auth::id()]);
    }

    public function update(Request $request){
        $general_setting = GeneralSetting::firstOrNew(['user_id' => \Auth::id()], ['user_id' => \Auth::id()]);
        $general_setting->calendar_json = [
            'type' => $request->type,
            'mode' => $request->mode,
            'weekday' => $request->weekday,
        ];

        $general_setting->save();
        return response()->json($general_setting);
    }

}
