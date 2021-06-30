<?php

namespace App\Repositories\Interfaces;

interface MessageRepositoryInterface
{
	public function storeMessage($data);
	public function getConversationsBetween($fromUserId,$toUserId);
	public function getConversationList($userId);
	public function countUnreadMessagesFromUsers($userId);
	public function markMessageAsRead($messageId,$userId);
	public function markAllMassagesAsRead($fromUserId,$toUserId);
}