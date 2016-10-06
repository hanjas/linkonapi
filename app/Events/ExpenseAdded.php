<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\User;
use App\Expense;

class ExpenseAdded extends Event {

	use SerializesModels;

    public $user, $expense;
    
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Expense $expense)
	{
		$this->user = $user;
        $this->expense = $expense;
	}

}
