<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

class DeleteTaskList extends Command implements SelfHandling {

	protected $user, $project, $tasklist;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, TaskList $tasklist)
	{
		$this->user = $user;
		$this->tasklist = $tasklist;
		$this->project = $tasklist->project;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->tasklist->delete();
		event(new UnFeedableEvent('TaskListDeleted',$this->tasklist));
	}

}
