<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\JoinProjectRequest;
use App\Http\Requests\UpdateProjectUserRequest;
use App\Http\Requests\LeaveProjectRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use App\Commands\CreateProject;
use App\Commands\DeleteProject;
use App\Commands\UpdateProject;
use JWTAuth;

class ProjectController extends Controller {

	public function __construct() {
		$this->middleware('project.auth', ['only' => ['update', 'destroy']]);
        $this->middleware('privilege', ['only' => ['index', 'store']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Project::all();
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
       	 $user = JWTAuth::parseToken()->authenticate();
        $this->validate($request, ['name' => 'required', 'description' => 'required']);
       	// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
        $project = $this->dispatch(new CreateProject($user, $request->all()));
        return response()->json(['success' => true, 'message' => 'Project Created.', 'project' => $project->load('users')]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Request $request)
	{
        $user = JWTAuth::parseToken()->authenticate();
       	// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new UpdateProject($user, $project, $request->all()));
        return response()->json(['success' => true, 'message' => 'Project Updated.','project' => $project ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
       	// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteProject($user, $project));
		return response()->json(['success' => true, 'message' => 'Project deleted.']);
	}

}
