<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserStatus;
use App\Repositories\Interfaces\UserStatusRepositoryInterface;

class UserStatusController extends Controller
{
    private $userStatusRepositoryInterface;

    public function __construct(
        UserStatusRepositoryInterface $userStatusRepositoryInterface
    )
    {
        $this->userStatusRepositoryInterface=$userStatusRepositoryInterface;
    }

    public function setMyStatusOnline()
    {
        $this->userStatusRepositoryInterface
             ->setStatusToOnline(auth()->user()->id);
    }

    public function setMyStatusOffline()
    {
         $this->userStatusRepositoryInterface
              ->setStatusToOffline(auth()->user()->id);
    }

    public function getUserStatus($userId)
    {
        $status=$this->userStatusRepositoryInterface
                     ->getUserStatus($userId)?'online':'offline';
        return response()->json([
               $status
        ]);
    }
}
