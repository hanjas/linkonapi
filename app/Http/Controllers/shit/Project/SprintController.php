<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use JWTAuth;
use App\Sprint;
use App;

class SprintController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->sprints;
	}

	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$sprint = App::make('App\Sprint')->create($request->only('name','release'));
		// $sprint = Sprint::create($request->only('name','release'));
		$project->sprints()->save($sprint);
		// $user->sprints()->save($sprint);
		return response()->json(['status'=>'success']);
	}
	

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Sprint $sprint, Request $request)
	{
		$sprint->update($request->all());
		return response()->json(['status'=>'success']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Sprint $sprint)
	{
		$sprint->delete();
		return response()->json(['status'=>'success']);
	}

}
