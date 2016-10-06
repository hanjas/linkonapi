<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Chat;
use App\MemberAction;
use App\Events\ChatUserLeft;

class LeaveChat extends Command implements SelfHandling {

	protected $user, $chat, $admin;
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Chat $chat, User $admin = null)
	{
		$this->user = $user;
		$this->chat = $chat;
		$this->admin = $admin;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->chat->users()->detach($this->user->id);
		event(new ChatUserLeft($this->user, $this->chat, $this->admin));
	}

}
