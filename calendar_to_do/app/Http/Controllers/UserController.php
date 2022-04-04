<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($id)
    {
        // authでその情報を表示させる
        $todo = ToDo::find($id);
        return response()->json($todo);
    }
}
