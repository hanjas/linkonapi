<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Project;
use App\Backlog;
use App\Http\Controllers\Controller;
use JWTAuth;
use Illuminate\Http\Request;

class BacklogController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->backlogs;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project,Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$backlog=Backlog::create($request->only('name'));
		$project->backlogs()->save($backlog);
		$user->backlogs()->save($backlog);
		return response()->json(['status'=>'success', 'backlog' => $backlog]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Backlog $backlog, Request $request)
	{
		$backlog->update($request->all());
		return response()->json(['status'=>'success', 'backlog' => $backlog]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Backlog $backlog)
	{
		$backlog->delete();
		return response()->json(['status'=>'success']);
	}

}
