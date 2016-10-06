<?php namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class HasProjectAccess {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $user = JWTAuth::parseToken()->authenticate();
        $project = $request->projects;
        if($project) {
            if($project->users()->whereUserId($user->id)->exists()) {
                return $next($request);
            }
        } else {
            $project = Project::find($request->get('project'));
            if($project->users()->whereUserId($user->id)->exists()) {
                return $next($request);
            }
        }
        return response()->json(['fail' => true, 'message' => 'Project Access Denied.'], 403);
	}

}
