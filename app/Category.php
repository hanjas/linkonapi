<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Exam;

class Category extends Model {

	public function exams() {
		return $this->belongsToMany(\App\Exam::class);
	}

	public function attachExam(Exam $exam) {
		return $this->exams()->attach($exam);
	}
	public function detachExam(Exam $exam) {
		return $this->exams()->detach($exam);
	}

}
