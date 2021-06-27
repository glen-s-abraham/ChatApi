<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserStatus;
use App\Traits\UserStatusTraits;

class UserStatusController extends Controller
{
    use UserStatusTraits;

    public function setMyStatusOnline()
    {
        $this->setStatusToOnline(auth()->user()->id);
    }

    public function setMyStatusOffline()
    {
        $this->setStatusToOffline(auth()->user()->id);
    }

    public function getUserStatus($userId)
    {
        $status=$this->isUserOnline($userId)?'online':'offline';
        return response()->json([
                "status"=>1,
                "status"=>$status,
        ]);
    }
}
