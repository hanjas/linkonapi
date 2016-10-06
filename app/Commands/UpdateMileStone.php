<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\MileStone;
use Illuminate\Database\Eloquent\Collection;
use App\Events\FeedableEvent;

class UpdateMileStone extends Command implements SelfHandling {

	protected $user, $milestone, $data;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, MileStone $milestone, array $data, Collection $audience = null)
	{
		$this->user = $user;
		$this->milestone = $milestone;
		$this->data = $data;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->milestone->update($this->data);
		event(new FeedableEvent('MileStoneUpdated', $this->user, $this->milestone, null));
	}

}
