<?php

namespace App\Repositories\Interfaces;

interface BroadcastRepositoryInterface
{
	public function createBroadcastChannel($userId);
	public function getBroadcastChannelName($userId);
	
}