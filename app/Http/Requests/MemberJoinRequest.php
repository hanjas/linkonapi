<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class MemberJoinRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'memberable_type' => 'required|in:project,task,chat',
            'memberable_id' => 'required'
		];
	}

}
