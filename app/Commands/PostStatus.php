<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Status;
use App\Project;
use App\Events\StatusPosted;
use App\Events\FeedableEvent;
use Illuminate\Database\Eloquent\Collection;

class PostStatus extends Command implements SelfHandling {

	protected $user, $data, $audience, $project;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, array $data, Project $project = null, Collection $audience = null)
	{
		$this->user = $user;
		$this->data = $data;
		$this->audience = $audience;
        $this->project = $project;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{	
		$status = Status::create($this->data);
		$this->user->statuses()->save($status);
		if($this->project) $this->project->statuses()->save($status);
		event(new FeedableEvent('StatusPosted', $this->user, $status));
		return $status;
	}

}
