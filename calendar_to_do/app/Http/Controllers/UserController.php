<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = User::find($request->user()->id);
        $user->fill($request->only(['name', 'email']));
        $user->save();
        return;
    }
}
