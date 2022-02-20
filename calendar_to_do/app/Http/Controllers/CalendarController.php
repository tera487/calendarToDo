<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index(){
        $calendar_items = Calendar::get(['name','start','end','id'])->toArray();
        return array_reverse($calendar_items);
    }

    public function store(Request $request){
        $calendar=new Calendar;
        $calendar->fill($request->only(['name', 'start','end']));
        $calendar->user_id = Auth::id();
        $calendar->save();
        return response()->json($calendar->id);
    }

    public function show($id)
    {
        $todo = ToDo::find($id);
        return response()->json($todo);
    }


    public function update($id, Request $request){
        $calendar=Calendar::find($id);
        $calendar->fill($request->only(['name', 'start','end']));
        $calendar->user_id = Auth::id();
        return $calendar->save();
    }

    public function destroy($id)
    {
        $todo = ToDo::find($id);
        $todo->delete();

        return response()->json('ToDo deleted!');
    }
}
