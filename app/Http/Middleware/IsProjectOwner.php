<?php namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use JWTAuth;
use App\Project;

class IsProjectOwner {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$user = JWTAuth::parseToken()->authenticate();
        $project = $request->projects;
        if($project) {
            if($project->users()->whereUserId($user->id)->whereType('owner')->exists()) {
                return $next($request);
            }   
        } else {
            $project = Project::find($request->get('project'));
            if($project) {
                if($project->users()->whereUserId($user->id)->whereType('owner')->exists()) {
                    return $next($request);
                }
            }
        }
		return response()->json(['fail' => true, 'message' => 'Not project owner.'], 403);
	}

}
