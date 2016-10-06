<?php namespace App\Http\Controllers\Project\Task;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CheckListController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project, Task $task)
	{
		return $task->checklists;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Task $task, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$checklist = $this->dispatch(new CreateCheckList($user, $project, $task, $request->all(), $audience));
		return response()->json(['success' => true, 'message' => 'Checklist created.', 'checklist' => $checklist]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Task $task, CheckList $checklist, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new UpdateCheckList($user, $project, $task, $checklist, $request->all(), $audience));
		return response()->json(['success' => true, 'message' => 'Checklist updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Task $task, CheckList $checklist)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteCheckList($user, $project, $task, $checklist, $audience));
		return response()->json(['success' => true, 'message' => 'Checklist deleted']);
	}

}
