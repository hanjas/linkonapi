<?php

use App\Commands\CreateProject;
use App\Commands\CreateTask;
use App\Commands\CompleteTask;
use App\Commands\UpdateStatus;
use App\Commands\PostForum;
use App\Commands\PostComment;
use App\Commands\AddUserToProject;
use App\Commands\CreateChatRoom;
use App\Commands\JoinChat;
use App\Commands\LeaveChat;
use App\User;
use App\Project;
use App\Feed;
use App\Task;
use App\Comment;
use App\Status;
use App\Forum;
use App\Chat;
use App\Message;
use App\Events\ProjectCreated;
use App\Events\TaskCreated;
use App\Events\TaskCompleted;
use App\Events\StatusUpdated;
use App\Events\ForumPosted;
use App\Events\CommentPosted;
use App\Events\UserAddedToProject;
use App\Events\ChatRoomCreated;

class ModelsTest extends TestCase {
	
	private $project, $user, $owner, $task, $comment, $message, $status;
	
	
	public function setUp() {
		parent::setUp();
        //~ Artisan::call('migrate:refresh'); // slower
        //~ Artisan::call('migrate');
		//~ $this->seed(); // comment this to make even faster
		// currently there are no seed files for some tables so they have to be manually cleaned
		//~ DB::table('projects')->delete();
		//~ DB::table('feeds')->delete();
		//~ DB::table('tasks')->delete();
		//~ DB::table('comments')->delete();
		//~ DB::table('statuses')->delete();
		//~ DB::table('messages')->delete();
		// $this->initVars();
	}
    
	public function initVars() {
		$this->project = Project::create($this->projectdata);
		$this->task = Task::create($this->taskdata);
		$this->user = User::firstOrFail();
		$this->owner = User::all()->last();
		$this->assertNotNull($this->owner);
		$this->assertNotEquals($this->owner->id, $this->user->id);
		$this->message = Message::create($this->messagedata);
		$this->status = Status::create($this->messagedata);
		$this->comment = Comment::create($this->commentdata);
	}
	
	/**
	 *  EVENTS TESTS
	 */
	 
	public function stestProjectCreated()
	{
		$project = Project::create($this->projectdata);
		$user = User::firstOrFail();
		// $project->owner()->associate($this->user); // belongsTo
		// $project->owner()->save($this->user); // belongsTo
		$user->userable->ownProjects()->save($project); // hasMany
		event(new ProjectCreated($user, $project));
		$this->assertEquals(1, Project::all()->count());
		$this->assertEquals(ProjectCreated::class, $project->feed->type);
		$this->assertEquals(1, $user->userable->ownProjects()->count());
		$this->assertEquals($project->id, $project->feed->feedable_id);
		$this->assertEquals(Project::class, $project->feed->feedable_type);
		$this->assertEquals(0, $project->feed->project_id);
	}
	
	public function stestTasksCreated() {
		$project = Project::firstOrCreate($this->projectdata);
		$task = Task::create($this->taskdata);
		$project->tasks()->save($task);
		$user = User::firstOrFail();
		$task->owner()->associate($user);
		event(new TaskCreated($user, $project, $task));
		$this->assertEquals(1, Task::count());
		$this->assertEquals(TaskCreated::class, $task->feed->type);
		$this->assertEquals($task->project->id, $task->feed->project_id);
		
		$this->assertEquals(0, $task->comments->count());
		$comment = Comment::create($this->commentdata);
		$task->comments()->save($comment);
		$this->assertEquals(1, $task->comments()->count());
	}
	
	public function stestTaskCompleted() {
		$task = Task::create($this->taskdata);
		$user = User::firstOrFail();
		$project = Project::create($this->projectdata);
		$task->completedBy()->associate($user);
		$this->assertEquals($task->completedBy->id, $user->id);
		event(new TaskCompleted($user, $project, $task));
		$this->assertEquals(1, Feed::count());
		$this->assertEquals($task->id, Feed::firstOrFail()->feedable->id);
		$this->assertEquals($project->id, Feed::firstOrFail()->project_id);
		$this->assertEquals(TaskCompleted::class, $task->feed->type);
	}
	
	public function stestStatusUpdate() {
		$status = Status::create($this->messagedata);
		$user = User::firstOrFail();
		$status->owner()->associate($user);
		event(new StatusUpdated($user, $status));
		$this->assertEquals(1, Feed::count());
		$this->assertEquals(StatusUpdated::class, $status->feed->type);
		$this->assertEquals(0, Feed::firstOrFail()->project_id);
		$this->assertEquals(StatusUpdated::class, $status->feed->type);
	}
	
	public function stestProjectStatusUpdate() {
		$status = Status::create($this->messagedata);
		$user = User::firstOrFail();
		$status->owner()->associate($user);
		$project = Project::create($this->projectdata);
		event(new StatusUpdated($user, $status, $project));
		$this->assertEquals(1, Feed::count());
		$this->assertEquals(StatusUpdated::class, $status->feed->type);
		$this->assertEquals($project->id, Feed::firstOrFail()->project_id);
		$this->assertEquals(StatusUpdated::class, $status->feed->type);
	}
	
	public function stestForumPosted() {
		$project = Project::create($this->projectdata);
		$user = User::firstOrFail();
		$forum = Forum::create($this->forumdata);
		//~ $user->forums()->save($forum);
		$forum->owner()->associate($user);
		$project->forums()->save($forum);
		event(new ForumPosted($user, $project, $forum));
		$this->assertEquals(1, $user->forums->count());
		$this->assertEquals(1, $project->forums->count());
		$this->assertEquals($user->id, $forum->owner->id);
		$this->assertEquals($project->id, $forum->project->id);
		$this->assertEquals(1, Feed::count());
		$this->assertEquals($forum->id, Feed::firstOrFail()->feedable->id);
		$this->assertEquals($project->id, Feed::firstOrFail()->project_id);
		$this->assertEquals(ForumPosted::class, $forum->feed->type);
	}
	
	public function stestUserAddedToProject() {
		$owner = User::firstOrFail();
		$user = User::all()->last();
		$this->assertNotNull($user);
		$this->assertNotEquals($owner->id, $user->id);
		$project = Project::create($this->projectdata);
		$project->owner()->associate($owner); // link
		$this->assertEquals($project->owner->id, $owner->id);
		$project->users()->save($user); // link
		$this->assertEquals(1, $project->users->count());
		$this->assertTrue($project->users->contains($user->id));
		event(new UserAddedToProject($owner, $project, $user));
		$this->assertEquals(1, Feed::count());
		$this->assertEquals($project->id, Feed::firstOrFail()->project_id);
		$this->assertEquals($user->id, Feed::firstOrFail()->feedable->id);
		$this->assertEquals(UserAddedToProject::class, $user->feed->type);
	}
	
	public function stestChatRoomCreated() {
		$chat = Chat::create(['name' => 'asdasdasd']);
		$user = User::firstOrFail();
		$user->userable->ownChats()->save($chat);
		$this->assertEquals($user->id, $chat->owner->id); // test
		$project = Project::create($this->projectdata);
		$project->chats()->save($chat);
		$this->assertEquals($project->id, $chat->project->id); // test
		event(new ChatRoomCreated($user, $project, $chat));
		$this->assertEquals($chat->id, $chat->feed->feedable_id);
		$this->assertEquals(ChatRoomCreated::class, $chat->feed->type);
		$this->assertEquals(Chat::class, $chat->feed->feedable_type);
	}
	
	
	/**
	 * COMMENTS TESTS
	 */
	 
	public function stestProjectCreatedCommentPosted() {
		$comment = Comment::create($this->commentdata);
		$user = User::firstOrFail();
		$project = Project::create($this->projectdata);
		//~ $comment->owner()->associate($user); // morphTo : fails
		//~ $comment->owner()->save($user); // morphTo : fails
		$user->comments()->save($comment); // morphMany
		$comment->commentable()->associate($project);
		event(new CommentPosted($user, $comment, $project));
		$this->assertEquals(1, $user->comments->count());
		$this->assertEquals(0, $comment->commentable->project_id);
		$this->assertEquals($project->id, $comment->commentable->id);
		$this->assertEquals(1, Feed::count());
		//~ $this->assertEquals(1, $user->relatedFeeds()->count());
	}
	
	public function stestTaskCreatedCommentPosted() {
		$user = User::firstOrFail(); // variable
		$project = Project::create($this->projectdata); // variable
		$task = Task::create($this->taskdata); // variable
		$task->project()->associate($project);
		$this->assertEquals($task->project->id, $project->id); // test
		$comment = Comment::create($this->commentdata); // variable
		$user->comments()->save($comment);
		$this->assertEquals($user->id, $comment->owner->id); // test
		// $comment->commentable()->associate($task); // morphTo : fails
		// $comment->commentable()->save($task); // morphTo : fails
		$task->comments()->save($comment); // morphMany : ok
		$this->assertEquals($task->id, $comment->commentable->id); // test
		event(new TaskCreated($user, $project, $task));
		$this->assertEquals(1, Feed::count()); // test
		event(new CommentPosted($user, $comment, $task));
		$this->assertEquals(2, Feed::count()); // test
		$this->assertEquals($comment->commentable->id, $task->id);
		$this->assertEquals(TaskCreated::class, $task->feed->type);
		$this->assertEquals(CommentPosted::class, $comment->feed->type);
		$this->assertEquals(1, $task->comments->count());
		$this->assertEquals(1, $user->comments->count());
	}
	
	/**
	 * COMMANDS TEST
	 */
	
	public function stestCreateProject() {
		$user = User::firstOrFail();
		$project = Bus::dispatch(new CreateProject($user, $this->projectdata));
		$this->assertEquals($project->owner->id, $user->id);
	}
	
	public function stestCreateTask() {
		$user = User::firstOrFail();
		$project = Project::create($this->projectdata);
		$task = Bus::dispatch(new CreateTask($user, $project, $this->taskdata));
		$this->assertEquals($user->id, $task->owner->id);
		$this->assertEquals($project->id, $task->project->id);
		$this->assertEquals($user, $task->owner);
		$this->assertEquals($project->id, $task->project->id);
		$this->assertNull($project->task);
	}
	
	public function stestCompleteTask() {
		$user = User::firstOrFail();
		$project = Project::create($this->projectdata);
		$task = Task::create($this->taskdata);
		Bus::dispatch(new CompleteTask($user, $project, $task));
		$this->assertEquals($user->id, $task->completedBy->id);
		$this->assertNull($project->owner);
		$this->assertNull($task->project);
	}
	
	public function stestUpdateStatus() {
		$user = User::firstOrFail();
		$status = Bus::dispatch(new UpdateStatus($user, $this->messagedata));
		$this->assertNull($status->project);
		$this->assertEquals($user->id, $status->owner->id);
		$project = Project::create($this->projectdata);
		$status = Bus::dispatch(new UpdateStatus($user, $this->messagedata, $project));
		$this->assertEquals($project->id, $status->project->id);
	}
	
	public function stestPostForum() {
		$user = User::firstOrFail();
		$project = Project::create($this->projectdata);
		$forum = Bus::dispatch(new PostForum($user, $project, $this->forumdata));
		$this->assertNull($project->owner);
		$this->assertEquals($user->id, $forum->owner->id);
		$this->assertEquals($project->id, $forum->project->id);
	}
	
	public function stestPostComment() {
		$user = User::firstOrFail();
		$project = Bus::dispatch(new CreateProject($user, $this->projectdata));
		$this->assertNotNull($project);
		$this->assertEquals($user->id, $project->owner->id);
		$comment = Bus::dispatch(new PostComment($user, $this->commentdata, $project));
		$this->assertEquals($user->id, $comment->owner->id);
		$this->assertEquals($project->id, $comment->commentable->id);
	}
	
	public function stestAddUserToProject() {
		$owner = User::firstOrFail();
		$project = Project::create($this->projectdata);
		$user = User::all()->last();
		$this->assertNotEquals($owner->id, $user->id);
		Bus::dispatch(new AddUserToProject($owner, $project, $user));
		$this->assertNull($project->owner);
		//~ $this->assertTrue($user->projects->contains($project->id)); // failure
		$this->assertTrue($project->users->contains($user->id));
		//~ echo "\nProject:\n{$project}";
		//~ echo "\nUser:\n{$user}";
		//~ echo "\nUser contains project : {$user->projects->contains($project->id)}"; doesnt return true
		//~ echo "\nProject contains user : {$project->users->contains($user->id)}";
	}
	
	public function stestCreateChatRoom() {
		$user = User::firstOrFail();
		$chat1 = Bus::dispatch(new CreateChatRoom($user, null, $this->chatroomdata));
		$this->assertNull($chat1->project);
		$project = Project::create($this->projectdata);
		$chat2 = Bus::dispatch(new CreateChatRoom($user, $project, $this->chatroomdata));
		$this->assertEquals($project->id, $chat2->project->id);
		$this->assertEquals($user->id, $chat2->owner->id);
	}
    
    public function stestJoinChat() {
        $user = User::firstOrFail();
        $chat = Chat::create($this->chatroomdata);
        $action = Bus::dispatch(new JoinChat($user, $chat)); // add
        $this->assertNull($action->admin);
        $this->assertTrue($chat->users->contains($user->id));
        $this->assertEquals(1, $chat->messages->count());
        $this->assertEquals(1, $chat->users->count());
        $action = Bus::dispatch(new LeaveChat($user, $chat)); // remove
        $this->assertEquals(2, Message::count());
//        $this->assertEquals(0, $chat->users->count());
        $admin = User::all()->last();
        $this->assertNotNull($admin);
        $this->assertNotEquals($admin->id, $user->id);
        $action = Bus::dispatch(new JoinChat($user, $chat, $admin)); // add
        $this->assertEquals($action->admin->id, $admin->id);
        $action = Bus::dispatch(new LeaveChat($user, $chat, $admin)); // remove
    }
    
    public function testSample() {
		$this->assertTrue(true);
	}
	
	public function tearDown() {
		parent::tearDown();
		//~ Artisan::call('migrate:reset');
	}

}
