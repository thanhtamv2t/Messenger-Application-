<?php

namespace App\Http\Controllers;
use App\Events\FriendsRequest;
use Illuminate\Http\Request;
use App\User;
use App\Friends;
use App\Message;
use Auth;

class UserController extends Controller
{

    public function __construct()
    {

            //echo "hello";

    }
    public function index()
    {
        //
    }
    public function create()
    {
    
        return view('user.register');

    }
    public function store()
    {
        

        $user = User::create([
            'name' => request('user_name'),
            'email' => request('user_email'),
            'password' => bcrypt(request('user_password')),
            'user_level' => 1
        ]);

        echo $user;

    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function addFriend()
    {
        $to = User::where('email',request('user_mail'))->first();
        $from = Auth::user();

        if(Auth::user()->email==request('user_mail')){
            return [
                'status' => 'errs',
                'msg' => "You can not add your self"
            ];
        }
        $friends = User::where('email',request('user_mail'))->first();
        
        if(!!$friends == false){
            return [
                'status' => 'errs',
                'msg' => 'Email is not exists on our system.'
            ];
        }

        $request = Friends::where([
            'user_id' => Auth::user()->id,
            'friend_id' => $friends->id
        ])->count();

        if($request > 0){
            return [
                'status' => 'errs',
                'msg' => 'This user is already in your request list'
            ];
        }


        Friends::create([
            'user_id' => Auth::user()->id,
            'friend_id' => $friends->id,
            'status' => 0 //0: requested
        ]);
        broadcast(new FriendsRequest($from, $to))->toOthers();
        return [
            'status' => 'success',
            'msg' => 'Your request is sent to that user :)'
        ];
    }

    public function friends(){
        $flist = Friends::where(
        ['user_id'=>Auth::user()->id,
        'status' => 1]
        )
        ->join('users','friends.friend_id','=','users.id')
        ->get(['friend_id','name','email']);        
        return $flist;
    }
    public function friendsRequest(){
        $req = Friends::where([
            'friend_id'=>Auth::user()->id,
            'status' => 0
        ])->join('users','friends.user_id','=','users.id')
        ->get(['user_id','name','email']);
        $count = Friends::where([
            'friend_id'=>Auth::user()->id,
            'status' => 0
        ])->join('users','friends.user_id','=','users.id')
        ->count();

        return ['requestList'=>$req,
        'count' => $count];
    }

    public function requestResponse(){
        if(request()->only('response')){
            //Accept;
            $accept = Friends::where([
                'friend_id' => Auth::user()->id,
                'user_id' => request('user_id')
            ])->first();
            $accept->status = 1;
            $accept->save();

            //Create relations for current User
            $c = Friends::create([
                'friend_id' => request('user_id'),
                'user_id' => Auth::user()->id,
                'status' => 1
            ]);

            if($c){
                return [
                    'status' => 'success',
                    'msg' => 'Friend Request Accepted'
                ];
            }else
            {
                return ['status'=>'errs',
                        'msg' => 'Errs'
                ];
            }
        }else{
            Friends::where([
                'friend_id' => Auth::user()->id,
                'user_id' => request('user_id')
            ])->delete();

            return [
                'status' => 'success',
                'msg' => 'Denied'
            ];
        }
    }

    public function changeAvatar(){
        
    }
}
