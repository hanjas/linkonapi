<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Notification;

class NotificationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(User $user)
	{
		return $user->notifications;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(User $user, Request $request)
	{
		$notification = $user->notifications()->create($request->all());
		return response()->json(['success' => true, 'message' => 'Notification created.']);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(User $user, Notification $notification, Request $request)
	{
		$notification->update($request->all());
		return response()->json(['success' => true, 'message' => 'Notification updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(User $user, Notification $notification)
	{
		$notification->delete();
		return response()->json(['success' => true, 'message' => 'Notification deleted.']);
	}

}
