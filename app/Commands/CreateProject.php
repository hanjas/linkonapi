<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Project;
use App\Events\ProjectCreated;
use App\Events\FeedableEvent;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\StoreProjectRequest;

class CreateProject extends Command implements SelfHandling
// , ShouldBeQueued // queued
{

	// use InteractsWithQueue, SerializesModels; // queued
	protected $user, $data;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, array $data)
	{
		$this->user = $user;
		$this->data = $data;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$project = Project::create($this->data);
		$project->createdBy()->associate($this->user);
		$project->save();
		if(isset($this->data['owner'])) {
			$user = User::findOrFail($this->data['owner']);
			$user->projects()->save($project, ['type' => 'owner']);
		} else {
			$this->user->projects()->save($project, ['type' => 'owner']);
		}
		$_project = $project->private ? $project : null;
		event(new FeedableEvent('ProjectCreated', $this->user, $project,$_project));
		return $project;
	}

}
