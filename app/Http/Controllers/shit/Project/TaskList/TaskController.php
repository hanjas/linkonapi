<?php namespace App\Http\Controllers\Project\TaskList;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\TaskList;
use App\User;
use App\Task;
use JWTAuth;
use App\Commands\CreateTask;
use App\Commands\UpdateTask;
use App\Commands\DeleteTask;

class TaskController extends Controller {

	public function __construct() {
//		$this->middleware('jwt.auth');
		// $this->middleware('project.access');
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project, TaskList $tasklist)
	{
		return $tasklist->tasks;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, TaskList $tasklist, Request $request)
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
	public function update(Project $project, TaskList $tasklist, Task $task, Request $request)
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
	public function destroy(Project $project, TaskList $tasklist, Task $task)
	{
		$user = JWTAuth::parseToken()->authenticate();
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteTask($user, $task));
        return response()->json(['status' => 'success', 'message' => 'Task Deleted.']);
	}

}
