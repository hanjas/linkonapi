<?php namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class Privilege {

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
        if($user->privilege_level > 0) {
            return $next($request);
        }
        return response()->json(['fail' => true, 'message' => 'Not Enough Privileges.'], 403);
	}

}
