<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class UnFeedableEvent extends Event {

	use SerializesModels;
	private $type,$subject;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($type, $subject)
	{
		$this->type = $type;
		
		$this->subject = $subject;
		
	}

	public function getType() {
		return $this->type;
	}

	public function getSubject() {
		return $this->subject;
	}

}
