<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Forum;
use Illuminate\Database\Eloquent\Collection;
use App\Events\UnFeedableEvent;

class DeleteForum extends Command implements SelfHandling {

    protected $user, $forum, $audience;
    
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Forum $forum, Collection $audience = null)
	{
		$this->user = $user;
        $this->forum = $forum;
        $this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->forum->delete();
        event(new UnFeedableEvent('ForumDeleted', $this->forum));
	}

}
