<?php

namespace App\Repositories;
use App\Models\BroadcastChannel;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\BroadcastRepositoryInterface;

class BroadcastRepository implements BroadcastRepositoryInterface
{

	public function createBroadcastChannel($userId)
    {
        
        $channel=BroadcastChannel::create([
            'user_id'=>$userId,
            'channel_name'=>$userId.'@'.Str::random(6)
        ]);
        return $channel;
    }

    public function getBroadcastChannelName($userId)
    {
    	return BroadcastChannel::where('user_id',$userId)
                               ->get()->pluck('channel_name');
    }
}