<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken($user->email.'_Token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'Registered successfully',
        ];
        
        return response($response, 200);    //everything good
    }
    
    public function login(Request $request) {
        $fields = $request->validate([     
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        //check email
        $user = User::where('email', $fields['email'])->first();
        
        //check pass
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials!'
            ], 401);
        }
        
        $token = $user->createToken($user->email.'_Token')->plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'Logged in successfully',
        ];
        
        return response($response, 201);    //successfully created code
    }
    
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        
        return [
            'message' => 'Logged out', 
        ];
    }
}