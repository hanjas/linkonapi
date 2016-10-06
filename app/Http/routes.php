<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('collages', 'CollageController@index');
Route::get('collage/{id}', 'CollageController@show');

Route::get('courses', 'CourseController@index');
Route::get('course/{id}', 'CourseController@show');

Route::get('exams', 'ExamController@index');
Route::get('exam/{id}', 'ExamController@show');


Route::get('feedbacks', 'FeedbackController@index');

Route::get('categories', 'CategoryController@index');
Route::get('category/{id}', 'CategoryController@show');

// Route::controllers([
// 	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
// ]);

		
Route::group(array('prefix' => 'api'), function() {
//     Route::post('authenticate', 'AuthController@authenticate');
//     Route::get('authenticate/user', 'AuthController@getAuthenticatedUser');
//     Route::get('authenticate/{user?}', 'AuthController@index');

//     Route::get('user/{id}', 'UserController@show');
//     Route::get('users', 'UserController@index'); 
//     Route::post('users', 'UserController@store'); 


	Route::get('collages', 'CollageController@index');
	Route::get('collage/{id}', 'CollageController@show');

	Route::get('courses', 'CourseController@index');
	Route::get('course/{id}', 'CourseController@show');

	Route::get('exams', 'ExamController@index');
	Route::get('exam/{id}', 'ExamController@show');
	
	Route::get('feedbacks', 'FeedbackController@index');

	Route::get('categories', 'CategoryController@index');
	Route::get('category/{id}', 'CategoryController@show');
    
    

    Route::group(['middleware' => 'jwt.auth'], function() {

        Route::post('createcollage', 'CollageController@create');
		Route::post('createcourse/{id}', 'CourseController@create');
		Route::post('assigncourse/{collage_id}/{course_id}', 'CourseController@assignCourseToCollage');
		Route::post('createexam/{id}', 'ExamController@create');
		Route::post('assignexam/{course_id}/{exam_id}', 'ExamController@assignExamToCourse');
		Route::post('createfeedback', 'FeedbackController@create');
		Route::post('createcategory', 'CategoryController@create');
		Route::post('attachExam/{exam_id}/{category_id}', 'CategoryController@attachCategoryToExam');
		Route::post('dettachExam/{exam_id}/{category_id}', 'CategoryController@detachCategoryToExam');

    }); 

});
