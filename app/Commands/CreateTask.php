<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Project;
use App\Task;
use App\MileStone;
use App\TaskList;
use App\Events\TaskCreated;
use App\Events\FeedableEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CreateTask extends Command implements SelfHandling
// , ShouldBeQueued // queued
{

	// use InteractsWithQueue, SerializesModels; // queued
	protected $user, $project, $tasklist, $data, $audience;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, array $data, Project $project, TaskList $tasklist = null, Collection $audience)
	{
		$this->user = $user;
		$this->data = $data;
		$this->project = $project;
		$this->tasklist = $tasklist;
		$this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$task = Task::create($this->data);
		$this->project->tasks()->save($task);
		if($this->tasklist) $this->tasklist->tasks()->save($task);
		// task creator
		$task->createdBy()->associate($this->user);
		$task->save();
		// the task owners
		if($this->audience->count()) {
			$task->users()->saveMany($this->audience->all());
		} else {
			$task->users()->save($this->user);
		}
		event(new FeedableEvent('TaskCreated', $this->user, $task, $this->tasklist));
		return $task;
	}

}
