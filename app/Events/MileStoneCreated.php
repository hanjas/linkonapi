<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use App\User;
use App\Project;
use App\MileStone;
use Illuminate\Database\Eloquent\Collection;

class MileStoneCreated extends Event implements FeedableContract {

	use FeedableTrait;
	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project, MileStone $milestone, Collection $audience = null)
	{
		$this->origin = $user;
		$this->context = $project;
		$this->subject = $milestone;
		$this->audience = $audience;
		$project->project = $project;
	}

}
