<?php namespace App\Services;

use App\Feed;

class FeedMessageService {
	
	public function getFeedMessage(Feed $feed) {
		if($feed->type == \App\Events\ProjectCreated::class) {
			return "{$feed->origin->email} created a new project {$feed->subject->name}";
		} else if($feed->type == \App\Events\TaskCreated::class) {
			return "{$feed->origin->email} added a Task in {$feed->target->name}";
		} else if($feed->type == \App\Events\TaskCompleted::class) {
			return "{$feed->origin->email} completed Task in {$feed->target->name}";
		} else if($feed->type == \App\Events\StatusUpdated::class) {
			if($feed->target_id)
				return "{$feed->origin->email} posted Status in project {$feed->target->name}";
			else
				return "{$feed->origin->email} posted Status";
		} else if($feed->type == \App\Events\ForumPosted::class) {
			return "{$feed->origin->email} posted Forum in {$feed->target->name}";
		} else if($feed->type == \App\Events\CommentPosted::class) {
			return "{$feed->origin->email} posted comment";
		} else if($feed->type == \App\Events\UserAddedToProject::class) {
			return "{$feed->origin->email} added {$feed->subject->email} to {$feed->target->name}";
		} else if($feed->type == \App\Events\ChatRoomCreated::class) {
			if($feed->target_id)
				return "{$feed->origin->email} started Chat in {$feed->target->name}";
			else
				return "{$feed->origin->email} started Chat";
		}
	}
	
}
