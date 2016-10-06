<?php namespace App\Events\Traits;

trait FeedableEvent {
	
	protected $origin, $subject, $context, $audience;
	
	public function getOrigin() {
		return $this->origin;
	}
	
	public function getSubject() {
		return $this->subject;
	}
	
	public function getContext() {
		return $this->context;
	}
	
	public function getAudience() {
		return $this->audience;
	}
	
}
