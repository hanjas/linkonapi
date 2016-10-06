<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use App\User;
use App\Status;
use Illuminate\Database\Eloquent\Collection;


class StatusDeleted extends Event implements FeedableContract {

	use SerializesModels;
    use FeedableTrait;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Status $status, Collection $audience = null)
	{
		$this->origin = $user;
        $this->subject = $status;
        $this->context = $status->project;
        $this->audience = $audience;
        $this->project = $status->project;
	}

}
