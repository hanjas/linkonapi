<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Task;
use App\CheckList;
use App\Events\CheckListCreated;
use App\Events\FeedableEvent;

class CreateCheckList extends Command implements SelfHandling {

	protected $user, $task, $data, $audience;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Task $task, array $data, Collection $audience = null)
	{
		$this->user = $user;
		$this->task = $task;
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
		$checklist = CheckList::create($this->data);
		$this->task->checklists()->save($checklist);
		$this->user->checklists()->save($checklist);
		event(new FeedableEvent('CheckListCreated', $this->user, $this->checklist, $this->task, $this->task->project, $this->audience));
	}

}
