<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller
{
	public function test()
	{
		dd(Auth::user()->email);
	}

    public function login()
    {
    	return view('user.login');
    }
    public function do_login(Request $request)
    {

    		$login = $request->only('email','password');

    		if(Auth::attempt($login))
    		{
    			return redirect('/');
    		}else{
    			return back();
    		}

    }

    public function logout()
    {
		Auth::logout();

		return redirect('/');
    }
}
