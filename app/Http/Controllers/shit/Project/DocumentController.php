<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\Document;
use JWTAuth;
use File;
use App\Commands\CreateDocument;
use App\Commands\DeleteDocument;

class DocumentController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->documents;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Request $request)
	{
		$this->validate($request, [
			'file' => 'required', 
			// 'description' => 'required'
		]);
		$user = JWTAuth::parseToken()->authenticate();
		$filename = $request->get('file');
		$temp_path = public_path() . '/uploads/temp/';
		$upload_path = public_path() . '/uploads/';
		// return ['from' => $temp_path . $filename, 'to' => $upload_path . $filename]; // unwanted
		// copy($temp_path . $filename, $upload_path . $filename); // alt
		File::copy($temp_path . $filename, $upload_path . $filename);
		$data = array(
			'url' => $upload_path . $filename,
			// 'description' => $request->get('description') // unwanted
		);
		$document = $this->dispatch(new CreateDocument($user, $data, $project));
		// $document = $project->documents()->create($request->all()); // unwanted
		return response()->json(['status' => 'success', 'message' => 'Document uploaded.', 'document' => $document, 'feed' => $document->feed]);
		// return 'sthizu ok';
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Document $document, Request $request)
	{
		$document->update($request->all());
		return response()->json(['status' => 'success', 'message' => 'Document updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Document $document)
	{
		$document->delete();
		return response()->json(['status' => 'success', 'message' => 'Document deleted.']);
	}

}
