<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Feed;
use App\Project;
use App\TaskList;
use JWTAuth;
use Illuminate\Database\Eloquent\Collection;

class MeController extends Controller {

	/**
	 * 
	 */
	public function projects() {
		$user = JWTAuth::parseToken()->authenticate();
		return $user->projects()->with('tasks')->with('milestones')->get();
	}

	/**
	 * 
	 */
	public function feeds(Request $request, $project = null) {
		$user = JWTAuth::parseToken()->authenticate();
		if($project) {
			$_project = Project::findOrFail($project);
			return $_project->feeds;
		}
		$feeds = $user->feeds()
			// ->with('subject.owner') //
			// ->with('origin.userable') //
			// ->with('comments.owner') //
			->orderBy('updated_at', 'desc')->get();
			// ->map(function($feed) {
				// if($feed->context_type == 'App\Feed') {
					// $feed->context = Feed::whereId($feed->context_id)
					// ->with('subject.owner') //
					// ->with('origin.userable') //
					// ->with('comments.owner') //
					// ->with('context') //
					// ->first();
				// } else if(!$feed->context_type == '') {
                    // $feed->context = $feed->context;
                // }
				// return $feed;
			// })->filter(function($feed) {
				// return !Feed::whereContextId($feed->id)->whereContextType("App\Feed")->exists();
			// });
		return $feeds;
	}

	public function notifications() {
		$user = JWTAuth::parseToken()->authenticate();
		return $user->notifications;
	}

	public function tasks() {
		$user = JWTAuth::parseToken()->authenticate();
		$tasks = $user->tasks;
		$tasklists = TaskList::whereIn('id', $tasks->lists('task_list_id'))->get();
		return ['tasks' => $tasks, 'tasklists' => $tasklists];
	}

	public function tasklists() {
		$user = JWTAuth::parseToken()->authenticate();
		$tasklist_ids = $user->tasks->lists('task_list_id');
		$tasklists = TaskList::whereIn('id', $tasklist_ids)->get();
		return $tasklists;
	}

	public function milestones() {
		$user = JWTAuth::parseToken()->authenticate();
		return $user->milestones;
	}

	public function checklists() {
		$user = JWTAuth::parseToken()->authenticate();
		return $user->checklists;
	}

	public function statuses() {
		$user = JWTAuth::parseToken()->authenticate();
		return $user->statuses;
	}

}
