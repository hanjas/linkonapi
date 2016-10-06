<?php namespace App\Handlers\Events;

use App\Events\FeedableEvent;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Activity;

class CreateActivity {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  FeedableEvent  $event
	 * @return void
	 */
	public function handle(FeedableEvent $event)
	{

		$activity = Activity::create(['type' => $event->getType()]);
		$event->getSubject()->activities()->save($activity);
		if($event->getParticipants() !=null)$activity->participants()->saveMany($event->getParticipants()->all());
		if($event->getProject() != null) $activity->project()->associate($event->getProject());
		$activity->user()->associate($event->getUser());
		$activity->save();
	}

}
