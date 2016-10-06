<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Project;
use App\MileStone;
use App\Events\MileStoneCreated;
use App\Events\FeedableEvent;

class CreateMileStone extends Command implements SelfHandling {

	protected $user, $project, $data, $audience;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Project $project, array $data, Collection $audience = null)
	{
		$this->user = $user;
		$this->project = $project;
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
		$milestone = MileStone::create($this->data);
		$this->user->milestones()->save($milestone);
		$this->project->milestones()->save($milestone);
		event(new FeedableEvent('MileStoneCreated', $this->user, $milestone, null, $this->project, $this->audience));
		return $milestone;
	}

}
