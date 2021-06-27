<?php


namespace App\Services;

use App\Models\BroadcastChannel;
use App\Traits\UserStatusTraits;
use App\Events\MessageEvent;

class BroadcastService{

	use UserStatusTraits;

	public function createOrSelectBroadcastChannel($userId)
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

    public function sendMessageNotification($toUserId,$message)
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

}