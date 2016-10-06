<?php namespace App\Http\Controllers\Feed\Comment;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Feed;
use App\Comment;
use App\Attachment;

class AttachmentController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Feed $feed, Comment $comment)
	{
		return $comment->attachments;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Feed $feed, Comment $comment, Request $request)
	{
		$attachment = $comment->attachments()->create($request->all());
		return response()->json(['success' => true, 'message' => 'Attachment created.', 'attachment' => $attachment]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Feed $feed, Comment $comment, Attachment $attachment, Request $request)
	{
		$attachment->update($request->all());
		return response()->json(['success' => true, 'message' => 'Attachment updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Feed $feed, Comment $comment, Attachment $attachment)
	{
		$attachment->delete();
		return response()->json(['success' => true, 'message' => 'Attachment deleted.']);
	}

}
