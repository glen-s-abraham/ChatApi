<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageEvent;

class ChatController extends Controller
{
    public function message(Request $request)
    {
        if($request->has('message') && $request->has('toUser'))
        {
            event(new MessageEvent($request->only(['message']),auth()->user()->id,$request->only(['toUser'])));
        }

    }
}
