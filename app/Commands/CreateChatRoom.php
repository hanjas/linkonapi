<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Project;
use App\Chat;
use App\Events\ChatRoomCreated;
use App\Events\FeedableEvent;
use Illuminate\Database\Eloquent\Collection;

class CreateChatRoom extends Command implements SelfHandling {

	protected $user, $project, $data, $audience;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project = null, array $data, Collection $audience = null)
	{
		$this->user = $user;
		$this->project = $project;
		$this->data = $data;
		$this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$chat = Chat::create($this->data);
        $this->user->chats()->save($chat, ['type' => 'admin']);
		if($this->project) $this->project->chats()->save($chat);
		event(new FeedableEvent('ChatRoomCreated', $this->user, $chat, null, $this->project, $this->audience));
		return $chat;
	}

}
