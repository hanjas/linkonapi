<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\Status;
use App\Commands\PostStatus;
use App\Commands\DeleteStatus;
use JWTAuth;
use App\Feed;

class StatusController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
    	return Status::all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, ['message' => 'required']);
		$user = JWTAuth::parseToken()->authenticate();
       	// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
       	$project = Project::find($request->get('project'));
		$status = $this->dispatch(new PostStatus($user, $request->all(), $project));
		$feed = Feed::whereType('StatusPosted')->whereSubjectId($status->id)->first();
		return response()->json(['success' => true, 'message' => 'Status Posted.', 'status' => $status, 'feed' => $feed]);
		// return ['success' => true];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Status $status, Request $request)
	{
		$status->update($request->all());
		return response()->json(['success' => true, 'message' => 'Status Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Status $status, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
       	// $audience = User::whereIn('id', explode(',', $request->get('audience')))->get();
        $this->dispatch(new DeleteStatus($user, $status));
        return response()->json(['success' => true, 'message' => 'Status Deleted.']);
	}

}
