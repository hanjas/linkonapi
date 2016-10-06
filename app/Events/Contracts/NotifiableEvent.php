<?php namespace App\Events\Contracts;


interface NotifiableEvent {
	
	public function getData();

	public function getUser();

	public function getNotifiable();
	
}