<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends Controller
{

    public function id(Request $request) {

        $user = User::findOrFail($request->id);

        return response()->json(['user' => $user]);
        
    }

    public function show(User $user) {

        return response()->json(['user' => $user]);
        
    }

    public function index() {
        
        //http://127.0.0.1:8000/api/users?page=2
        $users = User::with('tasks')->paginate(10);
        
        return response()->json([
            'numberOfPages' => $users->lastPage(),
            'users' => $users
        ]);

    }

    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'user.name' => 'required',
            'user.description' => 'required',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $user = (object)$request->user;

        $userToUpdate = User::findOrFail($user->id);

        $userToUpdate->name = $user->name;
        $userToUpdate->description = $user->description;

        $userToUpdate->save();

        return response()->json([
            'user' => $userToUpdate,
        ]);
    }

    public function destroy($id) {

        User::findOrFail($id)->delete();

        return response()->json([
            'ok'
        ]);
    }
}