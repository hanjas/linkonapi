<?php namespace App\Events;

use App\Events\Event;
use App\Events\Contracts\FeedableEvent as FeedableContract;
use App\Events\Traits\FeedableEvent as FeedableTrait;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Comment;
use App\Feed;

class CommentPosted extends Event implements FeedableContract {

	use SerializesModels;
	use FeedableTrait;
	
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Comment $comment, Feed $feed, Collection $audience = null)
	{
		$this->origin = $user;
		$this->subject = $comment;
		$this->context = $feed;
		$this->audience = $audience;
		$this->project = $feed->project;
	}
	
}
