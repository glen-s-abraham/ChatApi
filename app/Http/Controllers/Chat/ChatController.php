<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageEvent;
use Illuminate\Support\Str;
use App\Models\BroadcastChannel;

class ChatController extends Controller
{

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

    public function setUserStatusToOnline()
    {
        $status=auth()->user()->userStatus;
        $status->online=true;
        return $status;
    }

    public function message(Request $request)
    {
        if($request->has('message') && $request->has('toUser'))
        {
            event(new MessageEvent($request->only(['message']),auth()->user()->id,$request->only(['toUser'])));
        }

    }
}
