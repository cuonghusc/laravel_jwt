<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:api',['except'=>['login']]);
    }
    public function login()
    {
    	$credentials = request(['email','password']);
    	$token = null;
    	if(!$token=auth()->attempt($credentials)){
    		return response()->json([
    			'error'	=>	'Unauthorized'
    		],401);
    	}
    	return $this->responseWithToken($token);
    }
    public function me()
    {
    	return response()->json(auth()->user());
    }
    public function logout()
    {
    	auth()->logout();
    	return response()->json([
    		'message'	=>	'Success logged out'
    	]);
    }
    public function refresh()
    {
    	return $this->responseWithToken(auth()->refresh());
    }
    public function responseWithToken($token)
    {
    	return response()->json([
    		'access_token'	=>	$token,
    		'type_token'	=>	'bearer',
    		'expires_in'	=>	auth()->factory()->getTTL()*60,
    	]);
    }
}
