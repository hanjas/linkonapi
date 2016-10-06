<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\Milestone;
use JWTAuth;
use App\Commands\CreateMileStone;
use App\Commands\DeleteMileStone;
use App\Commands\UpdateMileStone;

class MileStoneController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->milestones;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$milestone = $this->dispatch(new CreateMileStone($user, $project, $request->all()));
		return response()->json(['success' => true, 'message' => 'Project Milestone Created.', 'milestone' => $milestone, 'feed' => $milestone->feed]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, MileStone $milestone, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new UpdateMileStone($user, $milestone, $request->except('token')));
		return response()->json(['success' => true, 'message' => 'Project Milestone Updated']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, MileStone $milestone, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteMileStone($user, $project, $milestone, $audience));
		return response()->json(['success' => true, 'message' => 'Project Milestone Deleted.']);
	}

}
