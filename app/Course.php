<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

	public function exams() {
		return $this->belongsToMany(\App\Exam::class);
	}

	public function subjects() {
		return $this->belongsToMany(\App\Subject::class);
	}

	public function collages() {
		return $this->belongsToMany(\App\Collage::class);
	}

	public function saveExam(\App\Exam $exam) {
		$this->exams()->save($exam);
	}

}
