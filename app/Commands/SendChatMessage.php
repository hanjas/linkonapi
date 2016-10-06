<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Message;
use App\Chat;
use App\Events\FeedableEvent;

class SendChatMessage extends Command implements SelfHandling {

	protected $user, $chat, $data;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Chat $chat, array $data)
	{
		$this->user = $user;
		$this->chat = $chat;
		$this->data = $data;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$message = Message::create($this->data);
		$this->user->messages()->save($message);
		$this->chat->messages()->save($message);
		event(new FeedableEvent('MessagePosted', $this->user, $message, $this->chat, $this->chat->project));
		return $message;
	}

}
