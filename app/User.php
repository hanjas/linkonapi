<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'username', 'first_name', 'last_name', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', 'userable_id', 'userable_type'];
	
	protected $appends = ['user_type'];

	// protected $with = ['profile'];

	public function sprints(){
		return $this->hasMany('App\Sprint');
	}

	public function stories() {
		return $this->belongsToMany('App\Story', 'stories_users');
	}

	public function createdStories() {
		return $this->hasMany('App\Story');
	}

	public function backlogs(){
		return $this->hasMany('App\Backlog');
	}

	public function getUserTypeAttribute() {
		$tokens = explode('\\', $this->userable_type);
		return $tokens[sizeof($tokens)-1];
	}
	
	public function userable() {
		return $this->morphTo();
	}

	public function profile() {
		return $this->hasOne(\App\Profile::class);
	}

	public function messages() {
		return $this->hasMany('App\Message');
	}
	
	public function forums() {
		return $this->hasMany('App\Forum');
	}
	
	public function projects() {
		return $this->belongsToMany('App\Project', 'users_projects')->withPivot('type')->withTimestamps();
	}
	
	public function chats() {
		return $this->belongsToMany('App\Chat', 'users_chats')->withPivot('type')->withTimestamps();
	}

	public function milestones() {
		return $this->hasMany('App\MileStone');
	}

	public function tasklists() {
		return $this->hasMany('App\TaskList');
	}
	
	public function tasks() {
		return $this->belongsToMany('App\Task', 'users_tasks')->withTimestamps();	
	}

	public function checklists() {
		return $this->hasMany('App\CheckList');
	}
		
	public function statuses() {
		return $this->hasMany('App\Status');
	}
	
	public function images() {
		return $this->morphMany('App\Image', 'imageable');
	}
	
	public function notifications() {
		return $this->hasMany('App\Notification');
	}
    
    public function comments() {
		return $this->hasMany('App\Comment');
	}
    
    public function scopePortal($query) {
        return $query->where('userable_type', 'App\PortalUser');
    }
    
    public function scopeClient($query) {
        return $query->where('userable_type', 'App\ClientUser');
    }
    
	public function feeds() {
		return $this->belongsToMany('App\Feed', 'feeds_users')->withPivot('read');
	}
	
	public function ownChats() {
		return $this->hasMany('App\Chat', 'user_id');
	}
	
	public function createdProjects() {
		return $this->hasMany('App\Project', 'created_by_id');
	}

	public function ownProjects() {
		return $this->projects()->whereType('owner')->get();
	}

	public function ownTasks() {
		return $this->tasks()->whereType('owner')->get();
	}

	public function createdTasks() {
		return $this->hasMany('App\Task', 'created_by_id');
	}
	
    public function relatedFeeds() {
        $commonFeeds = Feed::common()->orderBy('created_at');
        $myFeeds = Feed::whereIn('project_id', $this->projects->lists('id'))->orderBy('created_at');
        return $commonFeeds->get()->merge($myFeeds);
    }
	
}
