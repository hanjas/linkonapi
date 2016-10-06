<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use App\User;
use App\Project;
use App\TaskList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TaskListCreated extends Event implements FeedableContract {

	use SerializesModels;
	use FeedableTrait;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project, Model $context = null, TaskList $tasklist, Collection $audience)
	{
		$this->origin = $user;
		$this->context = $context; // can be either Project or MileStone
		$this->subject = $tasklist;
		$this->audience = $audience;
		$this->project = $project;
	}

}
