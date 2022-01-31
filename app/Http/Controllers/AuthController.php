<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8',
            // 'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        } else {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
    
            $token = $user->createToken($user->email.'_Token')->plainTextToken;
    
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => 'Registered successfully',
            ];
            
            return response($response, 200);    //everything good

        }

        // $fields = $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|string|unique:users,email',
        //     'password' => 'required|string|min:8',
        //     // 'password' => 'required|string|min:8|confirmed',
        // ]);

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