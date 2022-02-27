<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function index(){
        if(GeneralSetting::count() !== 0){
            $general_setting = GeneralSetting::first();
        }else{
            $general_setting = new GeneralSetting;
        }
        return $general_setting;
    }

    public function store(Request $request){
        $attributes = $request->only(['title', 'content', 'start_date','end_date']);
        GeneralSetting::create($attributes);
        return response()->json('ToDo created!');
    }

    public function show($id)
    {
        $todo = ToDo::find($id);
        return response()->json($todo);
    }


    public function update(Request $request){
        if(GeneralSetting::count() !== 0){
            $general_setting = GeneralSetting::first();
        }else{
            $general_setting = new GeneralSetting;
        }
        $general_setting->calendar_json = [
            'type' => $request->type,
            'mode' => $request->mode,
            'weekday' => $request->weekday,
        ];

        $general_setting->save();
        return response()->json($general_setting);
    }

    public function destroy($id)
    {
        $todo = ToDo::find($id);
        $todo->delete();

        return response()->json('ToDo deleted!');
    }
}
