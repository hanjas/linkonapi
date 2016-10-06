<?php namespace App\Http\Controllers\Chat;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JWTAuth;
use App\Chat;
use App\User;
use App\Commands\JoinChat;
use App\Commands\LeaveChat;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Chat $chat)
	{
		return $chat->users;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Chat $chat, Request $request)
	{
		$admin = JWTAuth::parseToken()->authenticate();
		$user = User::findOrFail($request->get('user_id'));
		// $users = User::whereIn('id', explode(',', $request->get('users')))->get();
		$this->dispatch(new JoinChat($user, $chat, $admin));
		return response()->json(['success' => true, 'message' => 'User Joined Chat.']);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Chat $chat, User $user, Request $request)
	{
		$chat->users()->updateExistingPivot($user->id, ['type' => $request->get('type')]);
		return response()->json(['success' => true, 'message' => 'Chat User Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Chat $chat, User $user)
	{
		$admin = JWTAuth::parseToken()->authenticate();
		$this->dispatch(new LeaveChat($user, $chat, $admin));
		return response()->json(['success' => true, 'message' => 'User Left Chat.']);
	}

}
