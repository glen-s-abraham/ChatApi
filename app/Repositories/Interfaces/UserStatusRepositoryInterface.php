<?php

namespace App\Repositories\Interfaces;

interface UserStatusRepositoryInterface
{
	public function setStatusToOnline($userId);
	public function setStatusToOffline($userId);
	public function getUserStatus($userId);
}