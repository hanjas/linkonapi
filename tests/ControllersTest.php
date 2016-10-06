<?php

use App\Commands\PostComment;
use App\Commands\PostStatus;
use App\Commands\DeleteComment;
use App\Commands\CreateTask;
use App\Feed;
use App\User;
use App\Project;

class ControllersTest extends TestCase {

	public function testSample() {
		$this->assertTrue(true);
	}

    public function stestHomeroute() {
        $response = $this->call('GET', '/api/home'); // home
        $this->assertResponseStatus(400);
        $response = $this->call('POST', '/api/projects'); // create project
        $this->assertResponseStatus(400);
        // $project = \App\Project::firstOrFail();
        // $response = $this->call('POST', "/api/projects/{$project}/tasks"); // create task
        // $this->assertResponseStatus(400);
    }
    
    public function stestAuthTest() {
        $credentials = [
            'email' => 'asd@g.com',
            'password' => 'asdasd'
        ];
        $response = $this->call('POST', '/api/authenticate', $credentials);
		$this->assertResponseStatus(200);
        $content = json_decode($response->getContent(), true);
        $token = $content['token'];
        //$response = $this->call('GET', '/api/authenticate/user', ['token' => $token]);
        //$this->assertResponseStatus(200);
        //$response = $this->call('GET', "/api/home?token={$token}"); // home
        // echo $response->getContent();
        $data = array_merge(['token' => $token], $this->projectdata);
        $response = $this->call('POST', "/api/projects", ['token' => $token]); // create projects
        echo $response->getContent();
        $this->assertResponseStatus(200);
    }
    
    public function stestComment() {
		$cd = array('comment' => 'lets bring the pain');
		$feed = Feed::firstOrFail();
		$commentCount = $feed->comments->count();
		echo "comment count {$commentCount}";
		$user = User::firstOrFail();
		$comment = Bus::dispatch(new PostComment($user, $cd, $feed));
		$commentCount2 = $feed->comments->count();
		// $this->assertEquals($comment->feed->id, $feed->id); // just test
		$this->assertEquals($comment->owner->id, $user->id); // just test
		// $this->assertNotEquals($commentCount, $commentCount2);
		$this->assertEquals($user->id, Feed::whereType('CommentPosted')->whereContextId($feed->id)->first()->origin->id);
		while($feed->comments->count() > 0) {
			$comment = $feed->comments->first();
			Bus::dispatch(new DeleteComment($user, $comment));
		}
		$this->assertFalse(Feed::whereType('CommentPosted')->whereContextId($feed->id)->exists());
		//~ echo "old count : {$count}, new count : {$ncount}";
	}

	/*
	 * Delete the only one seeded comment
	 */
	public function testDeleteComment() {
		$cd = array('comment' => 'lets bring the pain');
		$user = User::firstOrFail();
		$feed = Feed::firstOrFail();
		$comment = Bus::dispatch(new PostComment($user, $cd, $feed));
		// echo "deleting comment on {$feed->context->type}. total comments = {$feed->context->comments->count()}";
		Bus::dispatch(new DeleteComment($user, $comment));
		if($feed->comments->count() > 0) $this->assertEquals('CommentPosted', $feed->additional_type);
	}

	public function stestMakeModel() {
		$feed = $this->app->make('App\Feed')->firstOrFail();
		$this->assertNotNull($feed);
		echo "\n{$feed}";
	}
	
	public function stestDeleteFeed() {
		DB::table('comments')->delete();
		$cd = [
			'comment' => 'lets bring the pain'
		];
		$feed = Feed::firstOrFail();
		$user1 = User::firstOrFail();
		$user2 = User::all()->last();
		$comment1 = Bus::dispatch(new PostComment($user1, $cd, $feed));
		$comment2 = Bus::dispatch(new PostComment($user2, $cd, $feed));
		$commentFeed = Feed::whereType('App\Events\CommentPosted')->whereContextId($feed->id)->firstOrFail();
		$this->assertEquals($user2->id, $commentFeed->origin->id);
		$this->assertEquals($comment2->id, $commentFeed->subject->id);
		Bus::dispatch(new DeleteComment($user2, $comment2));
		$this->assertEquals($user1->id, $commentFeed->origin->id);
		$this->assertEquals($comment1->id, $commentFeed->subject->id);
	}

	public function testPostStatus() {
		$user = User::firstOrFail();
		$project = Project::firstOrFail();
		$status = Bus::dispatch(new PostStatus($user, ['message' => 'shimmu'], $project));
		$this->assertNotNull($status->project);
		$this->assertNotNull($status->owner);	
	}

	public function testTaskOwnership() {
		$project = Project::has('users', '>', 1)->first();
		$user1 = $project->users()->wherePrivilegeLevel('1')->first();
		$user2 = $project->users()->where('id', '!=', $user1->id)->first();
		// echo "users {$user1->id} {$user2->id}";
		$users = User::whereIn('id', [$user1->id, $user2->id])->get();
		$this->assertNotNull($users);
		$this->assertEquals(2, $users->count());
		$task = Bus::dispatch(new CreateTask($user1, ['name' => 'poopech'], $project, null, $users));
		$this->assertTrue($task->users->contains($user1->id));
		$this->assertTrue($task->users->contains($user2->id));
	}

}
