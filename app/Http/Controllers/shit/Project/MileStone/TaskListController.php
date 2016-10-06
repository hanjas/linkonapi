<?php namespace App\Http\Controllers\Project\MileStone;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\MileStone;
use App\TaskList;
use JWTAuth;
use App\Commands\CreateTaskList;
use App\Commands\DeleteTaskList;

class TaskListController extends Controller {

	// NOT COMPLETED

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project, MileStone $milestone)
	{
		return $milestone->tasklists;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, MileStone $milestone, Request $request)
	{
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$tasklist = $this->dispatch(new CreateTaskList($this->user, $project, $request->all()));
		return response()->json(['success' => true, 'message' => 'TaskList created.', 'tasklist' => $tasklist, 'feed' => $feed]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, MileStone $milestone, TaskList $tasklist, Request $request)
	{
		$tasklist->update($request->all());
		return response()->json(['success' => true, 'message' => 'TaskList updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, MileStone $milestone, TaskList $tasklist, Request $request)
	{
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteTaskList($this->user, $tasklist, $audience));
		return response()->json(['success' => true, 'message' => 'TaskList deleted.']);
	}

}
