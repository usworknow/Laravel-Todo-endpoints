<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    protected $status_code = 200;

    public function login()
    {
        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('RestApi')->accessToken;
            
            $result = array(
                'user' => $user,
                'token' => $token
            );

            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Authroized credential", "info" => $result]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Your email or password is incorrect!"]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"     => "required",
            "email"    => "required|email",
            "password" => "required"
        ]);

        // Valid registration.
        if ($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "Please fill out the Information.", "errors" => $validator->errors()]);
        }
        
        $userData = array(
            "name"     => $request->name,
            "email"    => $request->email,
            "password" => bcrypt($request->password)
        );
        
        $user_status = User::where("email", $request->email)->first();
        
        // Check duplicated email already in use.
        if (!is_null($user_status)) {
            return response()->json(["status" => "failed", "success" => false, "errors" => "Whoops! email already registered"]);
        }
        
        $user = User::create($userData);

        if (!is_null($user)) {
            //Success
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully", "data" => $user]);
        }
        else {
            //Error
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to register"]);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user()->token();
            $user->revoke();
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "User logged out!"]);
        }
        return response()->json(["status" => "failed", "success" => false, "message" => "failed to log out!"]);
    }
}