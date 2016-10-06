<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Document;
use Illuminate\Database\Eloquent\Collection;
use App\Events\UnFeedableEvent;

class DeleteDocument extends Command implements SelfHandling {

	protected $user, $doc, $audience;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, Document $doc, Collection $audience = null)
	{
		$this->user = $user;
		$this->doc = $doc;
		$this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		File::delete($this->doc->url);
		$this->doc->delete();
		event(new UnFeedableEvent('DocumentDeleted', $this->user, $this->document, null, $this->document->project, $this->audience));
	}

}
