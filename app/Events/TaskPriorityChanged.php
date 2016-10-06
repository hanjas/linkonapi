<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use App\User;
use App\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskPriorityChanged extends Event implements FeedableContract {

	use SerializesModels;
    use FeedableTrait;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $admin, Task $task, Collection $audience = null)
	{
		$this->origin = $admin;
        $this->subject = $task;
        $this->context = $task->project;
        $this->audience = $audience;
	}

}
