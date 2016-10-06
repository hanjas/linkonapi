<?php namespace App\Handlers\Events;

use App\Events\UnFeedableEvent;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Activity;

class DeleteActivity {

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
	 * @param  UnFeedableEvent  $event
	 * @return void
	 */
	public function handle(UnFeedableEvent $event)
	{
		if($event->gettype() == "CommentDeleted"){
			$subject = $event->getSubject();
			$lastActivity = Activity::where('subject_id', '=', $subject->id)->where('subject_type', '=', get_class($subject))->where('type', '=', 'CommentPosted')->last();
			$lastActivity->delete();
		}
	}

}
