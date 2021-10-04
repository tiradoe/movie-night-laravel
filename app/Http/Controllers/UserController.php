<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function updateUser(Request $request)
    {
        try {
            $user = User::findOrFail($request->user()->id);

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->username = $request->input('username');


            $user->save();

            return response()->json(['user' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => "Couldn't update user"
            ])->setStatusCode(404);
        }
    }
}
