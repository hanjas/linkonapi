<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Forum;
use App\Project;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use Illuminate\Database\Eloquent\Collection;

class ForumPosted extends Event implements FeedableContract {

	use SerializesModels;
	use FeedableTrait;
	
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project, Forum $forum, Collection $audience = null)
	{
		$this->origin = $user;
		$this->context = $project;
		$this->subject = $forum;
		$this->audience = $audience;
		$this->project = $project;
	}

}
