<?php namespace App\Http\Controllers\Project\Task;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\Task;
use App\User;
use JWTAuth;
use App\Commands\AddUserToTask;
use App\Commands\RemoveUserFromTask;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project, Task $task)
	{
		return $task->users;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Task $task, Request $request)
	{
		$admin = JWTAuth::parseToken()->authenticate();
		$user = User::findOrFail($request->get('user_id'));
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new AddUserToTask($admin, $task, $user, $audience));
		return response()->json(['success' => true, 'message' => 'User assigned to task.']);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Task $task, User $user, Request $request)
	{
		$task->users()->updateExistingPivot($user->id, $request->only('type'));
		return response()->json(['success' => true, 'message' => 'Task assignee updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Task $task, User $user, Request $request)
	{
		$admin = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new RemoveUserFromTask($admin, $task, $user, $audience));
		return response()->json(['success' => true, 'message' => 'User unassigned from task.']);
	}

}
