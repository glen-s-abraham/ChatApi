<?php

namespace App\Repositories;

use App\Repositories\Interfaces\MessageRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\User;
class MessageRepository implements MessageRepositoryInterface
{
	public function storeMessage($data)
	{
		$message=Message::create($data);
		return $message; 
	}

	public function getConversationsBetween($fromUserId,$toUserId)
	{
		$message=Message::where('from_user_id',$fromUserId)
                        ->where('to_user_id',$toUserId)
                        ->orWhere('from_user_id',$toUserId)
                        ->where('to_user_id',$fromUserId)
                        ->orderBy('created_at')
                        ->get();
        return $message;                
	}

	public function getConversationList($userId)
	{
		$messages=Message::where('from_user_id',$userId)
                          ->orWhere('to_user_id',$userId)
                          ->orderBy('created_at','desc')
                          ->get();

        $toIds=$messages->unique('to_user_id')->pluck('to_user_id')->toArray();
        $fromIds=$messages->unique('from_user_id')->pluck('from_user_id')->toArray();
        $list=array_unique(array_merge($toIds,$fromIds),SORT_REGULAR);
        return $list;
	}

	public function countUnreadMessagesFromUsers($userId)
	{
		$messages=DB::table('messages')
                     ->select(DB::raw('count(*) as unread,from_user_id'))
                     ->where('to_user_id',$userId)
                     ->where('read',0)
                     ->groupBy('from_user_id')
                     ->get();
        return $messages;             
	}

	public function markMessageAsRead($messageId,$userId)
	{
		$message=Message::findOrFail($messageId);
        if($message->to_user_id==$userId)
        {
            $message['read']=1;
            $message->save();
        }
        return 1;

	}

	public function markAllMassagesAsRead($fromUserId,$toUserId)
	{
		 Message::Where('from_user_id',$fromUserId)
                ->where('to_user_id',$toUserId)
                ->where('read',0)
                ->update(['read'=>1]);
	}
}