<?php namespace App\Http\Controllers\Project\MileStone\TaskList;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use App\Project;
use App\MileStone;
use App\TaskList;
use App\Task;
use App\Commands\CreateTask;
use App\Commands\UpdateTask;
use App\Commands\DeleteTask;

class TaskController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project, MileStone $milestone, TaskList $tasklist)
	{
		return $tasklist->tasks;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, Project $project, MileStone $milestone, TaskList $tasklist)
	{
		$this->validate($request, ['name' => 'required', 'description' => 'required']);
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('users')))->get();
		$task = $this->dispatch(new CreateTask($user, $request->all(), $project, $tasklist, $audience));
		return response()->json(['status' => 'success', 'message' => 'Task Created.', 'task' => $task]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, MileStone $milestone, TaskList $tasklist, Task $task, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
        $this->dispatch(new UpdateTask($user, $task, $request->all()));
        return response()->json(['status' => 'success', 'message' => 'Task Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, MileStone $milestone, TaskList $tasklist, Task $task)
	{
		$user = JWTAuth::parseToken()->authenticate();
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteTask($user, $task));
        return response()->json(['status' => 'success', 'message' => 'Task Deleted.']);
	}

}
