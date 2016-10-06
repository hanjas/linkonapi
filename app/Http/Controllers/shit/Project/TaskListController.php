<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use App\TaskList;
use App\Feed;
use App\Commands\CreateTaskList;
use App\Commands\DeleteTaskList;
use JWTAuth;

class TaskListController extends Controller {

    // public function __construct()
    // {
    //     $this->middleware('project.access');
    // }
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->tasklists;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Request $request)
	{
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->validate($request, ['name' => 'required']);
		$user = JWTAuth::parseToken()->authenticate();
		$tasklist = $this->dispatch(new CreateTaskList($user, $project, null, $request->all()));
		// $feed = Feed::whereSubjectType('App\TaskList')->whereSubjectId($tasklist->id)->first();
		return response()->json(['success' => true, 'message' => 'TaskList created.', 'tasklist' => $tasklist, 'feed' => $tasklist->feed]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, TaskList $tasklist, Request $request)
	{
		$tasklist->update($request->except('token'));
		 $this->dispatch(new UpdateTaskList($user, $tasklist, $request->all()));
		return response()->json(['success' => true, 'message' => 'TaskList updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, TaskList $tasklist, Request $request)
	{
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$user = JWTAuth::parseToken()->authenticate();
		$this->dispatch(new DeleteTaskList($user, $tasklist));
		return response()->json(['success' => true, 'message' => 'TaskList deleted.']);
	}

}
