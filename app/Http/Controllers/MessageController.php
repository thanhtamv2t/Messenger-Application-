<?php

namespace App\Http\Controllers;
use App\Events\MessageSent;
use App\Events\PrivateMessenger;
use Illuminate\Http\Request;
use App\Message;
use App\User;
use App\File;
use Auth;
class MessageController extends Controller
{
    public function addMessage(){
        $curOpen = request('curOpen');


        //Handling file uplaod and creaete file message;
        $file = null;
        $t = request('file');

        if($t != 'null'){
            request()->validate([
                'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            $imgName = Auth::user()->id.time().'.'.request('file')->getClientOriginalExtension();
            //Create file database;
            request('file')->move(public_path('uploads'), $imgName);

            $file = File::create([
                'name' => $imgName,
                'user_id'=>Auth::user()->id,
                'path'=> '/uploads/'.$imgName
            ]);

        }

        


    	$user = Auth::user();
        if($curOpen == null){
            $message = $user->messages()->create([
                'message' => strip_tags(request('message'),'<i>'),
                'to_id' => '99999'
            ]);            
 		     broadcast(new MessageSent($user, $message))->toOthers();
        }else{
            $message = $user->messages()->create([
                'message' => strip_tags(request('message'),'<i>'),
                'to_id' =>  $curOpen
            ]);
            $to = User::find($curOpen);
            broadcast(new PrivateMessenger($user,$to, $message))->toOthers();
            //Send File
            if($file){
                //Handling file type: 
                $img = ['png','gif','jpg','jpeg'];
                $ext = request('file')->getClientOriginalExtension();

                if(in_array($ext, $img)){
                   // $_msg = '<img src="/uploads/'.$file->name.'" alt="" class="send_file" id="'.$file->id.'">';
                    $_msg = "<img src='uploads/$file->name' alt='' class='send_file' id='$file->id'>";
                }
                else
                {
                    //$_msg = '<i class="fas fa-attach"></i><a href="/downloads/'.$file->id'">'.$file->name.'</a>';
                    $_msg = "<i class='fas fa-attach'></i><a href='/download/$file->id'>$file->name</a>";
                }
                $message = $user->messages()->create([
                    'message' => $_msg,
                    'to_id' => $curOpen
                ]);
                broadcast(new PrivateMessenger($user,$to,$message))->toOthers();
            }
        }
    	return ['status' => 'Message Sent',
        'test' => $message];

    }
    public function fetchMessage(){

        $r = request('curOpen');

        if($r == null)
        	return Message::with('User')
            ->get();
        else
            return Message::with('User')
            ->where('user_id',$r)
            ->where('to_id',Auth::user()->id)
            ->orWhere(function($q) use ($r) {
                $q->where('to_id', $r)
                ->where('user_id', Auth::user()->id);
            })
            ->orderBy('created_at','desc')
            ->take(20)
            ->get();
            
            
    }
}
