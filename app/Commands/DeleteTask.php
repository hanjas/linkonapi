<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Project;
use App\Task;
use App\Events\TaskDeleted;
use App\Events\UnFeedableEvent;
use Illuminate\Database\Eloquent\Collection;

class DeleteTask extends Command implements SelfHandling {

	protected $user, $project, $task;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Task $task)
	{
		$this->user = $user;
		$this->project = $task->project;
		$this->task = $task;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
//        $this->task->project_id = null;
//        $this->task->save();
		$this->task->delete();
		event(new UnFeedableEvent('TaskDeleted', $this->task));
	}

}
