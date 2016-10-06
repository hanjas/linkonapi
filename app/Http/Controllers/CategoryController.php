<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Category;
use App\Exam;

class CategoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$category = Category::all();
		return $category;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$category = new Category;
		$category->name = $request->name;
		$category->description = $request->description;
		$category->parent_id = $request->parent_id;
		$category->level = $request->level;
		$category->save();
		return;
	}
	public function attachCategoryToExam($exam_id, $category_id) {
		$exam = Exam::find($exam_id);
		$category = Category::find($category_id);
		$category->attachExam($exam);
		return;
	}
	public function detachCategoryToExam($exam_id, $category_id) {
		$exam = Exam::find($exam_id);
		$category = Category::find($category_id);
		$category->detachExam($exam);
		return;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$category = Category::with('exams')->find($id);
		return $category;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
