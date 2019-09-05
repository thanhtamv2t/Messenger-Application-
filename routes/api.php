<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('auth/register', 'AuthController@register');
Route::post('auth/login','AuthController@login');
Route::group(['middleware'=>'jwt.auth'], function(){
	Route::get('auth/user','AuthController@user');
	Route::post('auth/logout', 'AuthController@logout');
	//Message
	Route::post('get_message','MessageController@fetchMessage');
	Route::post('message','MessageController@addMessage');
	//Friends
	Route::post('friends','UserController@addFriend');
	Route::get('friends','UserController@friends');
	Route::get('friends_request','UserController@friendsRequest');
	Route::post('friend_request_repsonse','UserController@requestResponse');

});

Route::group(['middleware'=>'jwt.refresh'], function(){
	Route::get('auth/refresh','AuthController@refresh');
});