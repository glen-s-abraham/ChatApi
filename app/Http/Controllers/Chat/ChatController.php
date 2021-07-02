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
use App\Repositories\Interfaces\UserStatusRepositoryInterface;
use App\Http\Requests\MessageStoreRequest;

class ChatController extends Controller
{
    

    private $broadcastService;
    private $messageRepositoryInterface;
    private $broadcastRepositoryInterface;
    private $userStatusRepositoryInterface;

    

    public function __construct(BroadcastService $broadcastService,MessageRepositoryInterface $messageRepositoryInterface,
        BroadcastRepositoryInterface $broadcastRepositoryInterface,
        UserStatusRepositoryInterface $userStatusRepositoryInterface,
    )
    {
        $this->broadcastService=$broadcastService;
        $this->messageRepositoryInterface=$messageRepositoryInterface;
        $this->broadcastRepositoryInterface=$broadcastRepositoryInterface;
        $this->userStatusRepositoryInterface=$userStatusRepositoryInterface;

    }

    private function notifyUser($userId,$message)
    {
        if($this->userStatusRepositoryInterface
                    ->getUserStatus($userId)
            )
            {
                $sendChannel=$this->broadcastRepositoryInterface
                                  ->getBroadcastChannelName($userId)[0];

                $this->broadcastService->pushMessage($sendChannel,$message);            
            }
            else
            {
                $toUserMail=User::findOrFail($userId)->email;

                $this->broadcastService->sendEmailNotification(
                    $toUserMail,auth()->user()->name,$message
                );
            }
    }
    

    public function getMyBroadcastChannel()
    {
        return response()->json([
            $this->broadcastRepositoryInterface
                  ->getBroadcastChannelName(auth()->user()->id)[0]
        ]);
    }


    public function sendMessage(MessageStoreRequest $request)
    {
        
            $data=$request->only(['message','to_user_id']);
            $data['from_user_id']=auth()->user()->id;
            $message=$this->messageRepositoryInterface->storeMessage($data);
            $this->notifyUser($request->to_user_id,$message);
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
