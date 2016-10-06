<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Events\FeedableEvent;

class AddUserToTask extends Command implements SelfHandling {

    protected $admin, $task, $user, $audience;
    
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $admin, Task $task, User $user, Collection $audience = null)
	{
		$this->admin = $admin;
        $this->user = $user;
        $this->task = $task;
        $this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->task->users()->save($this->user, ['type' => 'member']);
        event(new FeedableEvent('UserAddedToTask', $this->admin, $this->task, $this->user, $this->task->project, $this->audience));
	}

}
