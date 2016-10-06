<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Project;
use App\CheckList;
use App\Events\CheckListDeleted;

class DeleteCheckList extends Command implements SelfHandling {

	protected $user, $project, $checklist, $audience;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project, CheckList $checklist, Collection $audience = null)
	{
		$this->user = $user;
		$this->project = $project;
		$this->checklist = $checklist;
		$this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->checklist->delete();
		event(new CheckListDeleted($this->user, $this->checklist, $this->audience));
	}

}
