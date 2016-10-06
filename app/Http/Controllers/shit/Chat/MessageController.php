<?php namespace App\Http\Controllers\Chat;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Chat;
use App\Message;
use App\Commands\SendChatMessage;
use JWTAuth;

class MessageController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Chat $chat)
	{
		return $chat->messages;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Chat $chat, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$message = $this->dispatch(new SendChatMessage($user, $chat, $request->all()));
		return response()->json(['success' => true, 'message' => 'Message Sent.', 'message' => $message]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Chat $chat, Message $message, Request $request)
	{
		$message->update($request->all());
		return response()->json(['success' => true, 'message' => 'Message Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Chat $chat, Message $message)
	{
		$message->delete();
		return response()->json(['success' => true, 'message' => 'Message Deleted.']);
	}

}
