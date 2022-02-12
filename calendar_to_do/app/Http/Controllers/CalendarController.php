<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(){
        $calendar_items = Calendar::all()->toArray();
        return array_reverse($calendar_items);
    }

    public function store(ToDoRequest $request){
        $attributes = $request->only(['title', 'content', 'start_date','end_date']);
        ToDo::create($attributes);
        return response()->json('ToDo created!');
    }

    public function show($id)
    {
        $todo = ToDo::find($id);
        return response()->json($todo);
    }


    public function update($id, ToDoRequest $request){
        $todo = ToDo::find($id);
        $todo->update($request->all());
        return response()->json('ToDo updated!');
    }

    public function destroy($id)
    {
        $todo = ToDo::find($id);
        $todo->delete();

        return response()->json('ToDo deleted!');
    }
}
