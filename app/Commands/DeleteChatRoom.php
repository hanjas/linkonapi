<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Chat;
use Illuminate\Database\Eloquent\Collection;
use App\Events\ChatRoomDeleted;
use App\Events\UnFeedableEvent;

class DeleteChatRoom extends Command implements SelfHandling {

    protected $user, $chat, $audience;
    
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Chat $chat, Collection $audience = null)
	{
		$this->user = $user;
        $this->chat = $chat;
        $this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->chat->delete();
        event(new UnFeedableEvent('ChatRoomDeleted', $this->user, $this->chat, null, $this->chat->project, $this->audience));
	}

}
