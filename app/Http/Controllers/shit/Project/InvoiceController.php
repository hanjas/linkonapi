<?php namespace App\Http\Controllers\Project;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Project;
use App\Invoice;

class InvoiceController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Project $project)
	{
		return $project->invoice;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Project $project, Request $request)
	{
		$invoice = $project->invoice()->create($request->all());
		return response()->json(['success' => true, 'message' => 'Project Invoice Created.', 'invoice' => $invoice]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Project $project, Invoice $invoice, Request $request)
	{
		$invoice->update($request->all());
		return response()->json(['success' => true, 'message' => 'Project Invoice Updated']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Project $project, Invoice $invoice)
	{
		$invoice->delete();
		return response()->json(['success' => true, 'message' => 'Project Invoice Updated']);
	}

}
