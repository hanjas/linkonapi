<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Project;
use App\MileStone;
use App\TaskList;
use App\Events\TaskListCreated;
use App\Events\FeedableEvent;

class CreateTaskList extends Command implements SelfHandling {

	protected $user, $project, $milestone, $data, $audience;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project, MileStone $milestone = null, array $data, Collection $audience = null)
	{
		$this->user = $user;
		$this->project = $project;
		$this->milestone = $milestone;
		$this->data = $data;
		$this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$tasklist = TaskList::create($this->data);
		// $tasklist->user()->associate($this->user); // belongsTo
		$this->user->tasklists()->save($tasklist);
		$this->project->tasklists()->save($tasklist);
		if($this->milestone) $this->milestone->tasklists()->save($tasklist);
		event(new FeedableEvent('TaskListCreated', $this->user, $tasklist));
		return $tasklist;
	}

}
