<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Comment;
use App\Events\CommentDeleted;
use App\Events\UnFeedableEvent;

class DeleteComment extends Command implements SelfHandling {

	protected $user, $comment, $commentable;
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Comment $comment)
	{
		$this->user = $user;
		$this->comment = $comment;
		$this->commentable = $comment->commentable;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->comment->delete();
		// $this->commentable->comments()->delete($this->comment); // bug
		event(new UnFeedableEvent('CommentDeleted',$this->commentable));
	}

}
