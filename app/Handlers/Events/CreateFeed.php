<?php namespace App\Handlers\Events;

use App\Events\Event;
use App\Events\FeedableEvent;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Feed;
use App\Project;
use App\User;
use App\Services\FeedMessageService;

class CreateFeed {

	protected $feeder;

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct(FeedMessageService $feeder)
	{
		$this->feeder = $feeder;
	}

	/**
	 * Handle the event.
	 *
	 * @param  Events  $event
	 * @return void
	 */
	public function handle(FeedableEvent $event)
	{
		if($event->getType() == 'CommentPosted') {
			/**
			 * if context has a previous comment feed, dont create new feed,
			 * instead associate its origin with the new one
			 * eg : ProjectCreated, TaskCompleted etc.
			 */
			$feed = $event->getContext();
			if($feed->additional_type != 'CommentPosted') {				
				$feed->additional_type = 'CommentPosted';
				$feed->additional_subject_type = get_class($event->getSubject());
			}
			$feed->additional_origin_id = $event->getOrigin()->id;
			$feed->additional_subject_id = $event->getSubject()->id;
			$feed->save();
			// $lastFeed = Feed::whereType('CommentPosted')->whereContextId($event->getContext()->id)->first();
			// if($lastFeed) {
				// echo "\n{$event->getOrigin()->id}";
				// $lastFeed->origin()->associate($event->getOrigin());
				// echo "\n{$lastFeed->origin->id}";
				// $lastFeed->subject()->associate($event->getSubject());
				// $lastFeed->save();
			// } else {
				// $this->_createFeed($event);
			// }
		} else if($event->getType() == 'TaskPercentChanged' ||
                  $event->getType() == 'TaskCompleted' ||
                  $event->getType() == 'TaskPriorityChanged' ||
                  $event->getType() == 'TaskUsersChanged') {
			$lastFeed = Feed::whereSubjectId($event->getSubject()->id)->first();
			if($lastFeed) {
				$lastFeed->type = $event->getType();
				$lastFeed->origin()->associate($event->getOrigin());
				$lastFeed->save();
			}
		} else {
			$this->_createFeed($event);
		}
        
//		echo "\n{$this->feeder->getFeedMessage($feed)}"; // debug
	}
	
	private function _createFeed(FeedableEvent $event) {
		$feed = new Feed;
		$feed->type = $event->getType();

		$feed->origin()->associate($event->getOrigin()); // origin
		if($event->getSubject() instanceof \Illuminate\Database\Eloquent\Collection) $feed->subjects()->saveMany($event->getSubject()->all());
		else $feed->subject()->associate($event->getSubject()); // subject
		$project = $event->getProject();
		if($project) $feed->project()->associate($project); // project
		$context = $event->getContext();
		if($context) $feed->context()->associate($context); // context
		$feed->save();
		
		if($event->getAudience() != null && $event->getAudience()->count() > 0) { // specified
			foreach($event->getAudience() as $audience) {
				$feed->users()->save($audience);
			}
		} else if($event->getProject()) { // project specific
			foreach($event->getProject()->users as $user) {
				$feed->users()->save($user);
			}
		} else { // default(all)
			$users = User::all();
			$feed->users()->saveMany($users->all());
		}
	}

}
