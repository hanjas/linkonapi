~<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {
	
	protected $projectdata = [
		'name' => 'asd',
		'description' => 'qweqwe',
	];
	
	protected $taskdata = [
		'name' => 'xcoivujos',
		'description' => 'oiurwe',
	];
	
	protected $commentdata = [
		'comment' => 'shitzu'
	];
	
	// can be used with both Message & Status
	protected $messagedata = [
		'message' => 'fuckzem'
	];
	
	protected $forumdata = [
		'name' => 'sjihisdf',
		'description' => 'dosifhbdof'
	];
	
	protected $chatroomdata = [
		'name' => 'iuxgsdf'
	];
	
	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}
    
}
