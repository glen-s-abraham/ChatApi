<?php

namespace App\Repositories\Interfaces;

interface BroadcastRepositoryInterface
{
	public function createOrSelectBroadcastChannel($userId);
	
}