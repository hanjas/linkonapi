<?php namespace App\Handlers\Events;

use App\Events\Event;
use App\Events\UnFeedableEvent;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Feed;

class DeleteFeed {

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
	public function handle(UnFeedableEvent $event)
	{
		// $event->getContext() : TaskCompleted Feed
		// $lastFeed : Comment Feed
		if($event->getType() == 'CommentDeleted') {
			$feed = $event->getContext();
			if($feed->additional_type == 'CommentPosted') {
				// echo "comment count : {$feed->comments->count()}";
				if($feed->comments->count() > 1) { // samshayamaaanu
					$lastComment = $feed->comments->last();
					// $feed->additional_subject_type = 'App\Comment';
					$feed->additional_subject_id = $event->getSubject()->id;
					$feed->updated_at = $lastComment->updated_at;
					$feed->save();
				} else {
					$feed->additional_type = '';
					$feed->additional_subject_id = 0;
					$feed->additional_subject_type = '';
					$feed->save();
				}
			}
		} else if($event->getType() == 'ProjectDeleted') {
            Feed::whereProjectId($event->getSubject()->id)->delete(); // delete all project related feeds
            Feed::whereSubjectId($event->getSubject()->id)->whereSubjectType('App\Project')->delete(); // create 'project created' feed
		} else {
            Feed::whereSubjectId($event->getSubject()->id)->delete();
        }
	}

}
