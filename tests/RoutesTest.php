<?php

class RoutesTest extends TestCase {

	protected $project, $user, $feed, $comment;

	protected $credentials = [
		'email' => 'asd@g.com',
		'password' => 'asdasd'
	];

	public function setUp() {
		parent::setUp();
		Artisan::call('migrate');
		Artisan::call('db:seed');
		$this->project = \App\Project::firstOrFail();
		$this->user = \App\User::firstOrFail();
		$this->feed = \App\Feed::firstOrFail();
		$this->comment = $this->feed->comments->first();
	}

	public function stestAllRoutesUnauthenticated() {
		$this->call('GET', '/api/authenticate/user');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/authenticate');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/authenticate?users=1,3');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/home');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/me/projects');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/me/tasks');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/me/notifications');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/me/tasks');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/me/tasklists');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/me/milestones');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/me/checklists');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/me/checklists');
		$this->assertResponseStatus(400);
		$this->call('GET', '/api/feeds');
		$this->assertResponseStatus(400);
		$this->call('GET', "/api/projects/{$this->project->id}/feeds");
		$this->assertResponseStatus(400);
		$this->call('POST', "/api/feeds/{$this->feed->id}/comments");
		$this->assertResponseStatus(400);
		$this->call('DELETE', "/api/feeds/{$this->feed->id}/comments/{$this->comment->id}"); // the comment is related to the feed
		$this->assertResponseStatus(400);
	}

	public function stestAllRoutesAuthenticated() {
		$response = $this->call('POST', '/api/authenticate', $this->credentials);
		$this->assertResponseOk();
		$credentials = ['token' => json_decode($response->getContent(), true)['token']];
		$token = $credentials['token'];
		$this->call('GET', '/api/authenticate/user', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/authenticate', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/authenticate?users=1,3', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/home', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/me/projects', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/me/tasks', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/me/notifications', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/me/tasks', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/me/tasklists', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/me/milestones', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/me/checklists', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/me/checklists', $credentials);
		$this->assertResponseOk();
		$this->call('GET', '/api/feeds', $credentials);
		$this->assertResponseOk();
		$this->call('GET', "/api/projects/{$this->project->id}/feeds", $credentials);
		$this->assertResponseOk();
		echo $this->call('POST', "/api/feeds/{$this->feed->id}/comments", [], [], [], ['HTTP_Authorization' => 'Bearer '.$token]);
		$this->assertResponseOk();
		$this->call('DELETE', "/api/feeds/{$this->feed->id}/comments/{$this->comment->id}", [], [], [], ['HTTP_Authorization' => 'Bearer '.$token]); // the comment is related to the feed
		$this->assertResponseOK();
	}

	public function testSample() {
		$this->assertTrue(true); // simple mock
	}

}
