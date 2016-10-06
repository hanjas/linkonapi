<?php namespace App\Http\Controllers\Feed;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;
use App\Commands\PostComment;
use JWTAuth;
use App;
use App\Comment;
use App\Commands\DeleteComment;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\DeleteCommentRequest;
use App\Feed;

class CommentController extends Controller {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, Feed $feed)
	{
		$this->validate($request, ['comment' => 'required']);
		$user = JWTAuth::parseToken()->authenticate();
		$comment = $this->dispatch(new PostComment($user, $request->all(), $feed));
		return response()->json(['status' => 'success', 'message' => 'Comment Posted.', 'comment' => $comment]);
	}

	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Feed $feed, Comment $comment, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		if($comment->owner->id != $user->id) return response()->json(['status' => 'fail', 'message' => 'Not authorized to delete comment.'], 403);
		$this->dispatch(new DeleteComment($user, $comment));
		return response()->json(['status' => 'success', 'message' => 'Comment Deleted.']);
	}

}
