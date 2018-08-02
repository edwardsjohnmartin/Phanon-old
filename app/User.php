<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Enums\Roles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id user's name 
 * @property string $name user's name 
 * @property string $email user's name 
 * @property string $pasword user's name 
 * @property \datetime $created_at when this object was added to the database
 * @property \datetime $updated_at when this object was last changed in the database
 */

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * Relationship function
     * Returns an array of courses this user has created
     */
    public function courses()
    {
        return $this->hasMany('App\Course', 'owner_id');
    }

    /**
     * Relationship function
     * Returns an array of courses this user is in
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class)->withPivot('role_id');
    }

    /**
     * Returns an array of all courses this user is participating in with the specified role
     */
    public function getCoursesByRole($role_id)
    {
        // Validate the role being requested exists
        if(Role::where('id', '=', $role_id)->exists()){
            return $this->enrolledCourses()->wherePivot('role_id', $role_id)->get();
        }
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team');
    }

    public function isAdmin()
    {
        foreach ($this->roles as $role){
            if ($role->name == Roles::ADMIN){
                return true;
            }
        }

        return false;
    }

    /**
     * Takes in a project_id and uses the currently logged-in user to find their team for that project.
     */
    public function teamForProject($project_id)
    {
        // Verify the project exists
        if(Project::find($project_id)->exists){
            // Get all ids of teams that belong to the project
            $project_team_ids = DB::table('project_teams')->where('project_id', $project_id)->pluck('team_id')->toArray();
            
            // Get the id of the team the logged-in user is a member of
            $team_id = DB::table('team_users')->whereIn('team_id', $project_team_ids)->where('user_id', auth()->user()->id)->pluck('team_id')->first();

            $team = Team::find($team_id);

            return $team;
        }

        return null;
    }

    public function projectSurveyResponses()
    {
        return $this->hasMany('App\ProjectSurveyResponse');
    }

    public function exerciseProgress()
    {
        return $this->hasMany('App\ExerciseProgress');
    }
}
