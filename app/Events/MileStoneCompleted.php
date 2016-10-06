<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use App\User;
use App\Project;
use App\MileStone;
use Illuminate\Database\Eloquent\Collection;

class MileStoneCompleted extends Event implements FeedableContract {

	use SerializesModels;
	use FeedableTrait;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, MileStone $milestone, Collection $audience = null)
	{
		$this->origin = $user;
		$this->subject = $milestone;
		$this->context = $milestone->project;
		$this->audience = $audience;
		$this->project = $milestone->project;
	}

}
