<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Expense;


class ExpenseController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Expense::all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$expense = Expense::create($request->all());
		return response()->json(['success' => true, 'message' => 'Expense Record Created.', 'expense' => $expense]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Expense $expense, Request $request)
	{
		$expense->update($request->all());
		return response()->json(['success' => true, 'message' => 'Expense Record Updated.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Expense $expense)
	{
		$expense->delete();
		return response()->json(['success' => true, 'message' => 'Expense Record Deleted.']);
	}

}
