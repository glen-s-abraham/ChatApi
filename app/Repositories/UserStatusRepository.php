<?php

namespace App\Repositories;
use App\Models\UserStatus;
use App\Repositories\Interfaces\UserStatusRepositoryInterface;

class UserStatusRepository implements UserStatusRepositoryInterface
{
	public function setStatusToOnline($userId)
	{
		if(UserStatus::where('user_id',$userId)->count()==1)
		{
			UserStatus::where('user_id',$userId)->update(['online'=>true]);
		}
		
	}

	public function setStatusToOffline($userId)
	{
		if(UserStatus::where('user_id',$userId)->get()->count()==1)
		{
			UserStatus::where('user_id',$userId)->update(['online'=>false]);
		}
		
	}


	public function getUserStatus($userId)
	{
		return UserStatus::where('user_id',$userId)->firstOrFail()->online;
	}
}