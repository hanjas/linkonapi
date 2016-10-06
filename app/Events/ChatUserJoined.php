<?php namespace App\Events;

use App\Events\Event;
use App\Events\Contracts\ChatEvent;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Chat;

class ChatUserJoined extends Event implements ChatEvent {

	use SerializesModels;
	
	protected $user, $chat, $admin;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Chat $chat, User $admin = null)
	{
		$this->user = $user;
		$this->chat = $chat;
		$this->admin = $admin;
	}
	
	public function getMessage() {
		return "{$this->user->email} joined chat";
	}
	
	public function getChat() {
		return $this->chat;
	}

}
