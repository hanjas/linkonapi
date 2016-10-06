<?php namespace App\Http\Controllers;


use App\Http\Requests\CreateChatRequest;
use App\Http\Requests\DeleteChatRequest;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JWTAuth;
use App\Commands\CreateChatRoom;
use App\Chat;
use App\User;
use App\Project;
use App\Commands\DeleteChatRoom;
class ChatController extends Controller {

	public function __construct() {
//		$this->middleware('jwt.auth');
//        $this->middleware('chat.admin');
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return Chat::all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$this->validate($request, ['name' => 'required']);
       	// $audience = User::whereIn('id', explode(',', $request->get('members')))->get();
        $chat = $this->dispatch(new CreateChatRoom($user, null, $request->all()));
        return response()->json(['status' => 'success', 'message' => 'Chat Created.', 'chat' => $chat, 'feed' => $chat->feed]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Chat $chat, Request $request)
	{
		$chat->update($request->all());
		return response()->json(['status' => 'success', 'message' => 'Chat Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Chat $chat, Request $request)
	{
        $user = JWTAuth::parseToken()->authenticate();
        if(!$chat->users()->whereType('admin')->whereUserId($user->id)->exists()) return response()->json(['status' => 'failure', 'message' => 'Access denied.'], 403);
       	// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
        $this->dispatch(new DeleteChatRoom($user, $chat));
        return response()->json(['status' => 'success', 'message' => 'Chat Deleted.']);
	}

}
