<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Status;
use App\Events\StatusDeleted;
use App\Events\UnFeedableEvent;
use Illuminate\Database\Eloquent\Collection;

class DeleteStatus extends Command implements SelfHandling {

	protected $user, $status, $project,$type;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct( $type, User $user, Status $status,$project)
	{
		$this->type = $type;
		$this->user = $user;
		$this->status = $status;
        $this->project = $project;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
//		$this->status->feeds()->delete();
		$this->status->delete();
		event(new UnFeedableEvent('StatusDeleted', $this->status));
	}

}
