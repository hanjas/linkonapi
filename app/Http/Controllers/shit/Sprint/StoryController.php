<?php namespace App\Http\Controllers\Sprint;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Sprint;

class StoryController extends Controller {

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Sprint $sprint, Collection $stories)
	{
		$sprint->stories()->saveMany($stories->all());
		return response()->json(['status'=>'success','message'=>'Stories added to sprint.', 'stories' => $stories]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Sprint $sprint, Collection $stories)
	{
		foreach($stories as $story) {
			if($story->sprint_id == $sprint->id) {
				$story->sprint_id = 0;
				$story->save();
			}
		}
		return response()->json(['status'=>'success','message'=>'Stories removed from sprint.','stories'=>$stories]);
	}

}
