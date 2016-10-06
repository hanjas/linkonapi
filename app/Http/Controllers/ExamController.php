<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Exam;
use App\Course;

class ExamController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$exams = Exam::all();
		return $exams;	
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request, $id)
	{
		$course = Course::find($id);

		$exam = new Exam;
		$exam->name = $request->name;
		$exam->apply_date = $request->apply_date;
		$exam->exam_date = $request->exam_date;
		$exam->save();

		$course->exams()->attach($exam);

		return redirect('/exams');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function assignExamToCourse($Course, $exam_id)
	{
		$course = Course::find($course_id);
		$exam = Exam::find($exam_id);
		$course->exams()->attach($exam);
		return redirect('/courses');
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
		$exam = Exam::with('courses.collages')->find($id);
		return $exam;
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
