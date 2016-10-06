<?php namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class ChatAdmin {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $chat = $request->chats;
        $user = JWTAuth::parseToken()->authenticate();
        if($chat) {
            if($chat->users()->whereUserId($user->id)->whereType('admin')->exists()) {
                return $next($request);
            }
        }
	}

}
