<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JWTAuth;
use App\Project;

class FeedController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		$user = JWTAuth::parseToken()->authenticate();
		return $user->feeds()
			->whereContextType('App\Project') // not needed actually
			->whereContextId($project->id)
			->with('subject.owner')
			->with('origin.userable')
			->with('context')
			->with('comments')
			->orderBy('updated_at')
			->get();
	}

}
