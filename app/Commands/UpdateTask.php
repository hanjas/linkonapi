<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Events\FeedableEvent;
use App\Events\TaskCompleted;
use App\Events\TaskPercentChanged;
use App\Events\TaskUsersChanged;
use App\Events\TaskPriorityChanged;
use DateTime;

class UpdateTask extends Command implements SelfHandling
// , ShouldBeQueued // queued
{

	// use InteractsWithQueue, SerializesModels; // queued
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
		$progress = $this->task->progress;
        $priority = $this->task->priority;
        $this->task->update($this->data);
        if($this->task->priority != $priority) {
            event(new FeedableEvent('TaskPriorityChanged', $this->user, $this->task, null, $this->task->project, $this->audience));
        }
        if($this->task->progress != $progress && $this->task->progress != 100) {
            event(new FeedableEvent('TaskProgressChanged', $this->user, $this->task, null, $this->task->project, $this->audience));
        }
        if($this->task->progress == 100) {
            $this->task->completed_at = new DateTime;
            $this->task->save();
            event(new FeedableEvent('TaskCompleted', $this->user, $this->task,$this->task->project));
        }
	}

}
