<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\Task;
use App\TaskList;
use App\User;
use App\Commands\CreateTask;
use App\Commands\CompleteTask;
use App\Commands\UpdateTask;
use App\Commands\DeleteTask;
use JWTAuth;

class TaskController extends Controller {

	public function __construct() {
		// $this->middleware('project.access');
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->tasks;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, Project $project)
	{
		$this->validate($request, ['name' => 'required']);
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('users')))->get();
		$tasklist = TaskList::find($request->get('task_list_id'));
		$task = $this->dispatch(new CreateTask($user, $request->all(), $project, null, $audience));
		return response()->json(['status' => 'success', 'message' => 'Task Created.', 'task' => $task, 'feed' => $task->feed]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Task $task, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
        $this->dispatch(new UpdateTask($user, $task, $request->all()));
        return response()->json(['status' => 'success', 'message' => 'Task Updated.', 'feed' => $task->feed]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Task $task)
	{
		$user = JWTAuth::parseToken()->authenticate();
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteTask($user, $task));
        return response()->json(['status' => 'success', 'message' => 'Task Deleted.']);
	}

}
