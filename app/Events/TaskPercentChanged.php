<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use App\User;
use App\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskPercentChanged extends Event implements FeedableContract {

	use SerializesModels;
    use FeedableTrait;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Task $task, Collection $audience = null)
	{
		$this->origin = $user;
        $this->context = $task->project;
        $this->subject = $task;
        $this->audience = $audience;
	}

}
