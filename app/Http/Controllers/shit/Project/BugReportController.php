<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\BugReport;

class BugReportController extends Controller {

    // public function __construct()
    // {
    //     $this->middleware('project.access');
    // }
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->bugreports;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Request $request)
	{
		$bugreport = $project->bugreports()->create($request->all());
		return response()->json(['success' => true, 'message' => 'Bugreport Created.', 'bugreport' => $bugreport]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, BugReport $bugreport, Request $request)
	{
		$bugreport->update($request->all());
		return response()->json(['success' => true, 'message' => 'Bugreport Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, BugReport $bugreport)
	{
		$bugreport->delete();
		return response()->json(['success' => true, 'message' => 'Bugreport Deleted.']);
	}

}
