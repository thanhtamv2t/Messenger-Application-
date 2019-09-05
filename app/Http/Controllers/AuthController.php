<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Auth;
class AuthController extends Controller
{
    public function register(){
    	$this->validate(request(),[
    		'name' => 'required',
    		'email' => 'required|email|unique:users',
    		'password' => 'required|string|min:6|max:10'
    	]);

    	$create = User::create([
    		'name' => request('name'),
    		'email' =>request('email'),
    		'password' => bcrypt(request('password')),
    		'user_level' => 1
    	]);
    	if($create)
    	{
    		return response([
    			'status'=>'success',
    			'data'=>$create
    		],200);
    	}

    }
    public function login(){

    	$user = request()->only('email','password');
        $type = request()->only('type','user');

        //Handling google Login or Register
        if(request('type')=="Google"){
            $u = json_decode(request('user'));
            $user = User::where('email',$u->email);
            $token = null;
            if($user->count() == 0 ){
                $create = User::create([
                    'name' => $u->name,
                    'email' => $u->email,
                    'password' => ' ',
                    'user_level' => 1
                ]);
                $token = JWTAuth::fromUser($create);
            }else{
                $token = JWTAuth::fromUser($user->first());
            }
            return response([
                    'status' => 'success'
                ])
                ->header('Authorization', $token); 

        }

    	if(!$token = JWTAuth::attempt($user)){
    		return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
    		],401);
    	}
    return response([
            'status' => 'success'
        ])
        ->header('Authorization', $token);    	
    }

	public function logout()
	{
	    JWTAuth::invalidate();
	    return response([
	            'status' => 'success',
	            'msg' => 'Logged out Successfully.'
	        ], 200);
	}    

    public function user(){
	    $user = User::find(Auth::user()->id);
	    return response([
	            'status' => 'success',
	            'data' => $user
	        ]);    	
    }
	public function refresh()
	{
	    return response([
	            'status' => 'success'
	        ]);
	}    
}
