<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Traits\UserStatusTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BroadcastChannel;
use App\Models\UserStatus;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\BroadcastService;

class ChatController extends Controller
{
    use UserStatusTraits;

    private $broadcastService;

    public function __construct(BroadcastService $broadcastService)
    {
        $this->broadcastService=$broadcastService;
    }
    

    public function getMyBroadcastChannel()
    {
        $channel=$this->broadcastService->createOrSelectBroadcastChannel(auth()->user()->id);
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

            $this->broadcastService->sendMessageNotification($request->toUser,$message);

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

    public function getMyConversationList()
    {
        $messages=Message::where('from_user_id',auth()->user()->id)
                          ->orWhere('to_user_id',auth()->user()->id)
                          ->orderBy('created_at','desc')
                          ->get();

        $toIds=$messages->unique('to_user_id')->pluck('to_user_id')->toArray();
        $fromIds=$messages->unique('from_user_id')->pluck('from_user_id')->toArray();
        $list=array_unique(array_merge($toIds,$fromIds),SORT_REGULAR);
        $list=User::find($list)->pluck('name','id');

        return response()->json(["myContacts"=>$list]);
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
