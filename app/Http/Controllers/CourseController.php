<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Course;
use App\Collage;

class CourseController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$course = Course::all();
		return $course;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request, $id)
	{
		$collage = Collage::find($id);
		$course = new Course;
		$course->name = $request->name;
		$course->description = $request->description;
		$course->save();
		$collage->courses()->attach($course);
		return redirect('/courses');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */

	public function assignCourseToCollage($collage_id, $course_id)
	{
		$collage = Collage::find($collage_id);
		$course = Course::find($course_id);
		$collage->courses()->attach($course);
		return redirect('/collages');
	}

	public function store()
	{
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$course = Course::with('exams')->find($id);
		return $course;
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
