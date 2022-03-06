<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function index(GeneralSetting $general_setting){
        return $general_setting;
    }

    public function store(Request $request){
        $attributes = $request->only(['title', 'content', 'start_date','end_date']);
        GeneralSetting::create($attributes);
        return response()->json('ToDo created!');
    }


    public function update(GeneralSetting $general_setting,Request $request){
    
        $general_setting->calendar_json = [
            'type' => $request->type,
            'mode' => $request->mode,
            'weekday' => $request->weekday,
        ];

        $general_setting->save();
        return response()->json($general_setting);
    }

}
