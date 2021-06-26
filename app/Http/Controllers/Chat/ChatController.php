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
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    use UserStatusTraits;

    private function createOrSelectBroadcastChannel($userId)
    {
        if(BroadcastChannel::where('user_id',$userId)->count()==1)
        {
            return BroadcastChannel::where('user_id',$userId)->get();
        }
        $channel=BroadcastChannel::create([
            'user_id'=>$userId,
            'channel_name'=>$userId.'@'.Str::random(6)
        ]);
        return $channel;
    }

    private function sendMessageNotification($toUserId,$message)
    {
        $sendChannel=BroadcastChannel::where('user_id',$toUserId)
                                     ->count();
        $userStatus=$this->isUserStatusPresent($toUserId);                            
        
        if($sendChannel==1 && $userStatus==1)
        {
            $sendChannel=BroadcastChannel::where('user_id',$toUserId)
                                         ->get()->pluck('channel_name');
            if($this->isUserOnline($toUserId))
            {
                event(new MessageEvent($message,$sendChannel[0]));
            }
                
        }      
    }

    public function getMyBroadcastChannel()
    {
        $channel=$this->createOrSelectBroadcastChannel(auth()->user()->id);
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

            $this->sendMessageNotification($request->toUser,$message);

            return response()->json([
                "status"=>1,
                "channel"=>$message,
            ]);                               
        }
    }

    public function getConversation($userId)
    {
        $message=Message::where('from_user_id',$userId)
                        ->where('to_user_id',auth()->user()->id)
                        ->orWhere('from_user_id',auth()->user()->id)
                        ->where('to_user_id',$userId)
                        ->orderBy('created_at')
                        ->get();
                        

        return response()->json([$message]);                    
    }

    public function getUnreadMessageCount()
    {
        $messages=DB::table('messages')
                     ->select(DB::raw('count(*) as unread,from_user_id'))
                     ->where('to_user_id',auth()->user()->id)
                     ->where('read',0)
                     ->groupBy('from_user_id')
                     ->get();

       return response()->json(["Unread messages"=>$messages]);
        
        
    }

    public function markAsread($messageId)
    {
        $message=Message::findOrFail($messageId);
        if($message->to_user_id==auth()->user()->id)
        {
            $message['read']=1;
            $message->save();
        }
        
    }

    public function markAllAsRead($fromUserId)
    {
        $message=Message::Where('from_user_id',$fromUserId)
                        ->where('to_user_id',auth()->user()->id)
                        ->where('read',0)
                        ->update(['read'=>1]);               
        
        
    }
}
