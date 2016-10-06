<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Project;
use App\Events\UserRemovedFromProject;
use App\Events\MemberRemoved;

class RemoveUserFromProject extends Command implements SelfHandling {

	protected $user, $project, $owner;
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $owner, Project $project, User $user)
	{
		$this->owner = $owner;
		$this->project = $project;
		$this->user = $user;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->project->users()->detach($this->user->id); // belongsToMany
		$this->user->feeds()->whereContextType('App\Project')->whereContextId($this->project->id)->delete();
		event(new UserRemovedFromProject($this->owner, $this->project, $this->user));
		return $this->user;
	}

}
