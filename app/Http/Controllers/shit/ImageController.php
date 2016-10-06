<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ImageController extends Controller {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$image = Image::create($request);
		return response()->json(['success' => true, 'message' => 'Image stored.', 'image' => $image]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Image $image)
	{
		$image->delete();
		return response()->json(['success' => true, 'message' => 'Image deleted.']);
	}

}
