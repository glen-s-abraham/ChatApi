<?php

namespace App\Traits;

use App\Models\UserStatus;

trait UserStatusTraits{

	public function setStatusToOnline($userId)
	{
		if(UserStatus::where('user_id',$userId)->count()==1)
		{
			UserStatus::where('user_id',$userId)->update(['online'=>true]);
			return;
		}
		UserStatus::create(['user_id'=>$userId,'online'=>true]);
	}

	public function setStatusToOffline($userId)
	{
		if(UserStatus::where('user_id',$userId)->get()->count()==1)
		{
			UserStatus::where('user_id',$userId)->update(['online'=>false]);
			return;
		}
		UserStatus::create(['user_id'=>$userId,'online'=>false]);
	}

	public function isUserStatusPresent($userId)
	{
		return UserStatus::where('user_id',$userId)
                                         ->count();
	}

	public function isUserOnline($userId)
	{
		return UserStatus::where('user_id',$userId)->firstOrFail()->online;
	}


}	