<?php namespace App\Handlers\Events;

use App\Events\Event;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Events\Contracts\ChatEvent;
use App\Message;

class PostMessage {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  Events  $event
	 * @return void
	 */
	public function handle(ChatEvent $event)
	{
		$message = Message::create([
			'message' => $event->getMessage()
		]);
//		$message->chat()->associate($event->getChat());
		$event->getChat()->messages()->save($message);
        
//        echo "\n{$message->message}";
	}

}
