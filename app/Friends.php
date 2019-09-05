<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Friends extends Model
{
    protected $fillable = ['user_id','friend_id','status'];
	//	
	public function friends()
	{
			return $this->belongsTo(User::class);
	}	
	public function add_friend($user_id, $friend_id)
	{
		$this->create([
			'user_id' => $user_id,
			'friend_id' => $friend_id,
			'status' => 'request'
		]);
		return true;
	}
	public function accept_friend($user_id, $friend_id)
	{
		$relation = $this::where([
			'user_id' => $user_id,
			'friend_id' =>$friend_id
		])->first();

		$relation->status = 'accepted';
		$relation->save();
		return true;
	}
	public function refuse_friend($user_id, $friend_id)
	{
		$relation = $this::where([
			'user_id' => $user_id,
			'friend_id' =>$friend_id
		])->first();

		$this::destroy($relation->id);
		return true;		
	}

}
