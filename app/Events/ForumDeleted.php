<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Forum;
use App\Project;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use Illuminate\Database\Eloquent\Collection;

class ForumDeleted extends Event implements FeedableContract {

	use SerializesModels;
    use FeedableTrait;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Forum $forum, Collection $audience = null)
	{
		$this->origin = $user;
        $this->subject = $forum;
        $this->context = $project;
        $this->audience = $audience;
        $this->project = $forum->project;
	}

}
