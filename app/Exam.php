<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model {

	public function categories() {
		return $this->belongsToMany(\App\Category::class);
	}
	public function courses() {
		return $this->belongsToMany(\App\Course::class);
	}

}
