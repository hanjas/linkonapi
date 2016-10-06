<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JWTAuth;
use App\Project;
use App\Forum;

class ForumController extends Controller {

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
		return $project->forums;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$forum = $this->dispatch(new PostForum($user, $project, $request->all(), $audience));
		return response()->json(['success' => true, 'message' => 'Forum Posted.', 'forum' => $forum]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Forum $forum, Request $request)
	{
		$forum->update($request->all());
		return response()->json(['success' => true, 'message' => 'Forum Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Forum $forum)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new DeleteForum($user, $forum, $audience));
		return response()->json(['success' => true, 'message' => 'Forum deleted.']);
	}

}
