<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use JWTAuth;
use App\Feed;
use App\Commands\AddUserToProject;
use App\Commands\RemoveUserFromProject;
use App\Http\Requests\JoinProjectRequest;
use App\Http\Requests\UpdateProjectUserRequest;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->users;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Request $request)
	{
		$this->validate($request, ['type' => 'required|in:owner,developer,client']);
		$admin = JWTAuth::parseToken()->authenticate();
        // $user = User::findOrFail($request->get('user_id'));
		$type = $request->get('type');
		$audience = User::whereIn('id', explode(',', $request->get('users')))->get();
		$this->dispatch(new AddUserToProject($admin, $project, $audience, $type));
		$feed = Feed::whereType('UserAddedToProject')->whereSubjectId($user->id)->whereProjectId($project->id)->first();
		return response()->json(['success' => true, 'message' => 'User Joined Project.', 'feed' => $feed]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, User $user, Request $request)
	{
		$this->validate($request, [
			'type' => 'in:owner,developer,client',
			'user_id' => 'exists:users_projects'
		]);
        $data = ['type' => $request->get('type')];
        $project->users()->updateExistingPivot($user->id, $data);
        return response()->json(['success' => true, 'message' => 'Project Member updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, User $user)
	{
		$admin = JWTAuth::parseToken()->authenticate();
		$audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
		$this->dispatch(new RemoveUserFromProject($admin, $project, $user, $audience));
		return response()->json(['success' => true, 'message' => 'User Left Project.']);
	}

}
