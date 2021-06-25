<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Traits\UserStatusTraits;
use Illuminate\Http\Request;
use App\Events\MessageEvent;
use Illuminate\Support\Str;
use App\Models\BroadcastChannel;
use App\Models\UserStatus;
use App\Models\Message;

class ChatController extends Controller
{
    use UserStatusTraits;
    public function getMyBroadcastChannel()
    {
        if(BroadcastChannel::where('user_id',auth()->user()->id)->count()==1)
        {
            return response()->json([
                "status"=>1,
                "channel"=>BroadcastChannel::where('user_id',auth()->user()->id)->get(),
             ]);
        }
        $channel=BroadcastChannel::create([
            'user_id'=>auth()->user()->id,
            'channel_name'=>auth()->user()->id.'@'.Str::random(6)
        ]);
        return response()->json([
            "status"=>1,
            "channel"=>$channel,
        ]);

    }



    public function sendMessage(Request $request)
    {
        if($request->has('message') && $request->has('toUser'))
        {
             
            $message=Message::create([
                'from_user_id'=>auth()->user()->id,
                'to_user_id'=>$request->toUser,
                'message'=>$request->message,
            ]);

            $sendChannel=BroadcastChannel::where('user_id',$request->toUser)
                                         ->count();

            $userStatus=$this->isUserStatusPresent($request->toUser);                            
            
            if($sendChannel==1 && $userStatus==1)
            {
                 $sendChannel=BroadcastChannel::where('user_id',$request->toUser)
                                         ->get()->pluck('channel_name');
                if($this->isUserOnline($request->toUser))
                {
                    event(new MessageEvent($message,$sendChannel[0]));
                }
                
            }                             
            
            return response()->json([
                "status"=>1,
                "channel"=>$message,
            ]);
           
                                        

        }

    }
}
