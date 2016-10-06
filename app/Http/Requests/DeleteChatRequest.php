<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use JWTAuth;
use App\Chat;

class DeleteChatRequest extends Request {

    public $user;
    
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        $this->user = JWTAuth::parseToken()->authenticate();
        $chatId = $this->route('chat');
        $chat = Chat::findOrFail($chatId);
        if($chat) {
            return $chat->users()->whereUserId($this->user->id)->whereType('admin')->exists();
        }
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
