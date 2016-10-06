<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ProfileController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(User $user)
	{
		return $user->profile;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(User $user, Request $request)
	{
		$profile = $user->profile()->create($request->all());
		return response()->json(['success' => true, 'message' => 'Profile created.', 'profile' => $profile]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(User $user, Profile $profile, Request $request)
	{
		$profile->update($request->all());
		return response()->json(['success' => true, 'message' => 'Profile updated.']);
	}
	
}
