<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Course;

class Collage extends Model {

	public function courses() {
		return $this->belongsToMany(Course::class);
	}

}
