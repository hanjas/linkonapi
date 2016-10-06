<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\User;

class AuthController extends Controller {

	public function __construct() {
		$this->middleware('jwt.auth', ['only' => ['index', 'getAuthenticatedUser']]);
	}

	public function authenticate(Request $request) {
		// return 'ok';
		$credentials = $request->only('email', 'password');
		// return $credentials;
		
		try {
			if(!$token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 401);
			}
		} catch(JWTException $e) {
			return response()->json(['error' => 'could_not_create_token'], 500);
		}
		$user = User::whereEmail($credentials['email'])->first();
		return response()->json(array(
			'token' => $token,
			'user' => $user,
			// 'projects' => $user->projects
		));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($users = null)
	{
		//~ $users = User::all();
		if($users) {
			$users = array_unique(explode(',', $users));
			return User::find($users);
		} else {
			return User::all();
		}
	}
    
    public function getAuthenticatedUser() {
        try {
            if(!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch(TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch(TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch(JWTException $e) {
            return $response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }

    public function register(Request $request) {
        $newuser = $request->all();
        $password = \Hash::make($request->get('password'));
        $newuser['password'] = $password;
        return User::create($newuser);
		// return $request->all();
		// return $request->get('password');
    }

}
