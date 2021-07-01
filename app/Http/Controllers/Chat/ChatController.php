<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Traits\UserStatusTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BroadcastChannel;
use App\Models\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\BroadcastService;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use App\Repositories\Interfaces\BroadcastRepositoryInterface;
use App\Http\Requests\MessageStoreRequest;

class ChatController extends Controller
{
    use UserStatusTraits;

    private $broadcastService;
    private $messageRepositoryInterface;
    private $broadcastRepositoryInterface;

    public function __construct(BroadcastService $broadcastService,MessageRepositoryInterface $messageRepositoryInterface,
        BroadcastRepositoryInterface $broadcastRepositoryInterface
    )
    {
        $this->broadcastService=$broadcastService;
        $this->messageRepositoryInterface=$messageRepositoryInterface;
        $this->broadcastRepositoryInterface=$broadcastRepositoryInterface;
    }
    

    public function getMyBroadcastChannel()
    {
        return response()->json([
            $this->broadcastRepositoryInterface
                  ->createOrSelectBroadcastChannel(auth()->user()->id)
        ]);
    }


    public function sendMessage(MessageStoreRequest $request)
    {
        
            $data=$request->only(['message','to_user_id']);
            $data['from_user_id']=auth()->user()->id;
            $message=$this->messageRepositoryInterface->storeMessage($data);
            $sendChannel=$this->broadcastRepositoryInterface
                              ->createOrSelectBroadcastChannel($request->to_user_id)
                              ->pluck('channel_name')[0];
            $toUserMail=User::findOrFail($request->to_user_id)
                            ->pluck('email')[0];                             
            $this->broadcastService->sendMessageNotification(
                $sendChannel,
                $message,
                $toUserMail,
                auth()->user()->name,
            );
            return response()->json([
                $message,
            ]);                               
        
    }

    public function getConversation($userId)
    {                    
        return response()->json([
            $this->messageRepositoryInterface
                 ->getConversationsBetween($userId,auth()->user()->id)
        ]);                    
    }

    public function getMyConversationList()
    {
        $list=$this->messageRepositoryInterface
                 ->getConversationList(auth()->user()->id);
        
        return response()->json([
            User::find($list)->pluck('name','id')
        ]);
    } 

    public function getUnreadMessageCount()
    {
       return response()->json([
            $this->messageRepositoryInterface
                 ->countUnreadMessagesFromUsers(auth()->user()->id)
       ]);
  
    }

    public function markAsread($messageId)
    {
        $this->messageRepositoryInterface
                 ->markMessageAsRead($messageId,auth()->user()->id);        
    }

    public function markAllAsRead($fromUserId)
    {
       $this->messageRepositoryInterface
            ->markAllMassagesAsRead($fromUserId,auth()->user()->id);                       
    }
}
