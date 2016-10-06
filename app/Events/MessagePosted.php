<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\Chat;
use App\Message;
use App\User;

class MessagePosted extends Event {

	use SerializesModels;
	
	protected $chat, $message, $user;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(Chat $chat, Message $message, User $user)
	{
		$this->chat = $chat;
		$this->message = $message;
		$this->user = $user;
	}
	
	public function getChat() {
		return $this->chat;
	}
	
	public function getMessage() {
		return $this->message;
	}
	
	public function getUser() {
		return $this->user;
	}

}
