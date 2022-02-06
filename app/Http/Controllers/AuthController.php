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
            'email' => 'required|email|unique:users,email',
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
    
            // if in db role_as is set to 1 give abilities
            // 1 == Admin
            if($user->role_as == 1) {
                //abilities admin
                $role = 'admin';
                $token = $user->createToken($user->email.'_AdminToken', ['server:admin'])->plainTextToken;
                
            }else {
                //abilities null
                $role = null;
                $token = $user->createToken($user->email.'_Token', [''])->plainTextToken;
            }
    
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => 'Registered successfully!',
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

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // $fields = $request->validate([     
        //     'email' => 'required|string',
        //     'password' => 'required|string',
        // ]);

        if ($validator->fails()) {
            $response = [
                'validation_errors'=>$validator->messages(),
            ];
            return response($response, 200);
        } else {
            
            //check email
            $user = User::where('email', $request->email)->first();
            
            //check pass
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => 'Bad credentials!'
                ], 401);
            } else {

                // if in db role_as is set to 1 give abilities
                // 1 == Admin
                if($user->role_as == 1) {
                    //abilities admin
                    $role = 'admin';
                    $token = $user->createToken($user->email.'_AdminToken', ['server:admin'])->plainTextToken;
                    
                }else {
                    //abilities null
                    $role = null;
                    $token = $user->createToken($user->email.'_Token', [''])->plainTextToken;
                }

                
                $response = [
                    'user' => $user,
                    'token' => $token,
                    'message' => 'Logged in successfully!',
                    'role' => $role,
                ];
                
                return response($response, 200); 
            }
            
        }

    }
    
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'Logged out successfully!',
        ];
        
        return response($response, 200); 
    }
}