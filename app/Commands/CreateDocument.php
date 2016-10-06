<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use App\Project;
use App\Document;
use Illuminate\Database\Eloquent\Collection;
use App\Events\FeedableEvent;

class CreateDocument extends Command implements SelfHandling {

	protected $user, $data, $project, $audience;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, array $data, Project $project, Collection $audience = null)
	{
		$this->user = $user;
		$this->data = $data;
		$this->project = $project;
		$this->audience = $audience;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$document = Document::create($this->data);
		event(new FeedableEvent('DocumentUploaded', $this->user, $document, null));
		return $document;
	}

}
