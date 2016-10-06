<?php namespace App\Events\Contracts;

interface ChatEvent {
	
	public function getMessage();
	
	public function getChat();
	
}
