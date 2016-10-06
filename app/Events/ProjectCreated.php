<?php namespace App\Events;

use App\Events\Event;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectCreated extends Event implements FeedableContract {

	use SerializesModels;
	use FeedableTrait;
	
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project, Collection $audience = null)
	{
		$this->subject = $project;
		$this->origin = $user;
		$this->audience = $audience;
		$this->project = $project;
	}
}
