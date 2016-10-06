<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Comment;
use App\Events\CommentPosted;
use App\Events\FeedableEvent;
use App\Feed;
use Illuminate\Database\Eloquent\Collection;

class PostComment extends Command implements SelfHandling
// , ShouldBeQueued // queued
{

	// use InteractsWithQueue, SerializesModels; // queued
	protected $user, $data, $feed, $audience;
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, array $data, Feed $feed, Collection $audience = null)
	{
		$this->user = $user;
		$this->data = $data;
		$this->feed = $feed;
		$this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$comment = Comment::create($this->data);
		$this->user->comments()->save($comment);
		$this->feed->comments()->save($comment);
		event(new FeedableEvent('CommentPosted', $this->user, $comment);
		return $comment;
	}

}
