<?php

namespace App\Http\Controllers;

use App\Models\ToDo;
use App\Http\Requests\ToDoRequest;

use Illuminate\Http\Request;

class ToDoController extends Controller
{
    public function index(){
        $todos = ToDo::all()->toArray();
        return array_reverse($todos);
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
