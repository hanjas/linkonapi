<?php namespace App\Events;

use App\Events\Event;
use App\Events\Contracts\NotifiableEvent;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Project;


class UserAddedToProject extends Event implements FeedableContract, NotifiableEvent {

	use SerializesModels;
	use FeedableTrait;
	
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $owner, Project $project, User $user, Collection $audience = null)
	{
		$this->subject = $user;
		$this->origin = $owner;
		$this->context = $project;
		$this->audience = $audience;
	}

	// TEMP notification implementation below.

	public function getData() {
		return [
			'type' => 'UserAddedToProject',
			'subject' => 'You have been added to a project.',
			'body' => "{$this->origin->first_name} added you to his project {$this->context->name}",
		];
	}

	public function getUser() {
		return $this->subject;
	}

	public function getNotifiable() {
		return $this->context;
	}

}
