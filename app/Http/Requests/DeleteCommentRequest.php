<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Comment;
use JWTAuth;

class DeleteCommentRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$commentId = $this->route('comment');
		$user = JWTAuth::parseToken()->authenticate();
		return Comment::whereId($commentId)->whereUserId($user->id)->exists();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			//
		];
	}

}
