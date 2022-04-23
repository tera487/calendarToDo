<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request, $id)
    {
        $file_name = $id . '_' . $request->icon->getClientOriginalName();
        $request->icon->storeAs('public', $file_name);

        $user = User::find($id);
        $user->fill($request->only(['name', 'email']));
        $user->icon_path = 'storage/' . $file_name;
        $user->save();
        return;
    }
}
