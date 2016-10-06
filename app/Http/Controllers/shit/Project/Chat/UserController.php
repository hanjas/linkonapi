<?php namespace App\Http\Controllers\Project\Chat;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project, Chat $chat)
	{
		return $chat->users;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Chat $chat, Request $request)
	{
		$admin = JWTAuth::parseToken()->authenticate();
		$user = User::findOrFail($request->get('user_id'));
		$this->dispatch(JoinChat($user, $chat, $admin));
		return response()->json(['success' => true, 'message' => 'User Joined Chat.']);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Chat $chat, User $user, Request $request)
	{
		$chat->users()->updateExistingPivot($user->id, $request->get('type'));
		return response()->json(['success' => true, 'message' => 'Chat User Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Chat $chat, User $user)
	{
		$admin = JWTAuth::parseToken()->authenticate();
		$this->dispatch(LeaveChat($user, $chat, $admin));
		return response()->json(['success' => true, 'message' => 'User Left Chat.']);
	}

}
