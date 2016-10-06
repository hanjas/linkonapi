<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Project;
use App\Events\ProjectDeleted;
use App\Events\UnFeedableEvent;
use Illuminate\Database\Eloquent\Collection;


class DeleteProject extends Command implements SelfHandling {

	protected $project, $user;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project)
	{
		
		$this->user = $user;
		$this->project = $project;
		
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->project->delete();
		event(new UnFeedableEvent('ProjectDeleted',$this->project));
	}

}
