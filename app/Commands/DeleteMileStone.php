<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

private $type,$project,$user;

class DeleteMileStone extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($type,$user,$project)
	{
		$this->user=$user;
		$this->type=$type;
		$this->project=$project;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->milestone->delete();
		event(new UnFeedableEvent('milestone deleted',$this->milestone));
	}

}
