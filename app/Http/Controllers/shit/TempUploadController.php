<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Input;
use JWTAuth;

class TempUploadController extends Controller {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, ['file' => 'required']);
		$file = Input::file('file');
		$token = JWTAuth::getToken();
		// if($file == null) return ['shitzu'];
        $extension = $file->getClientOriginalExtension();
		$filename = sha1($file->getClientOriginalName()) . sha1(time()) . ".{$extension}";
        $directory = public_path() . "/uploads/temp/";
        // if (!File::exists($directory)) mkdir($directory, 0777, true);
        $upload_success = $file->move($directory, $filename);
        return $upload_success ? response()->json(['status' => 'success', 'file' => $filename]) : response()->json(['status' => 'failure']);
        // return Input::all();
	}

}
