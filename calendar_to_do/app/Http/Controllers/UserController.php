<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->only(['name', 'email']));
        if (isset($request->icon_path)) {
            $file_name = $id . '_' . $request->icon_path->getClientOriginalName();
            $request->icon_path->storeAs('public', $file_name);
            $user->icon_path = 'storage/' . $file_name;
        }
        $user->save();
        return $user;
    }
}
