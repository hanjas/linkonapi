<?php namespace App\Handlers\Events;

use App\Events\Event;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Events\Contracts\NotifiableEvent;
use App\Notification;

class NotifyUser {

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
	 * @param  Events  $event
	 * @return void
	 */
	public function handle(NotifiableEvent $event)
	{
		$notification = Notification::create($event->getData());
		$notification->notifiable()->associate($event->getNotifiable());
		$notification->save();
		$event->getUser()->notifications()->save($notification);
	}

}
