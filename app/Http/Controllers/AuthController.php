<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]); //except login and register
    }

    //login
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    //logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    //refresh token
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    //get user info
    public function me()
    {
        return response()->json(auth()->user());
    }
    //get token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer', //type of token
            'expires_in' => auth()->factory()->getTTL() * 60, //time to live
        ]);
    }
    //register
    public function register(RegisterRequest $request)
    {
        $data = $request->all(); //get all request
        $data['password'] = bcrypt($request->password); //encrypt password
        $user = User::create($data); //create user
        $accessToken = $user->createToken('authToken')->accessToken; //create token
        return response(['user' => $user, 'access_token' => $accessToken]); //return user and token
    }
}
