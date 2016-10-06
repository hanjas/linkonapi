<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class FeedableEvent extends Event {

	use SerializesModels;

	private $type,$subject, $user, $project,$partcipants;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($type,$user,$subject,$project=null,$partcipants=null)
	{
		$this->type = $type;
		$this->subject = $subject;
		$this->user = $user;
		$this->project = $project;
		$this->partcipants = $partcipants;
		
	}

	public function getType() {
		return $this->type;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function getUser() {
		return $this->user;
	}

	public function getProject() {
		return $this->project;
	}

	public function getParticipants() {
		return $this->partcipants;
	}

}
	