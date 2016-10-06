<?php namespace App\Http\Controllers\Project\Feed;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CommentController extends Controller {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Feed $feed, StoreCommentRequest $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$input = $request->all();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$comment = $this->dispatch(new PostComment($user, $input, $feed, $audience));
		return response()->json(['success' => true, 'message' => 'Comment Posted.', 'comment' => $comment]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Feed $feed, Comment $comment, DeleteCommentRequest $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteComment($user, $comment, $audience));
		return response()->json(['success' => true, 'message' => 'Comment Deleted.']);
	}

}
