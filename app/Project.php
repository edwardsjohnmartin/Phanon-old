<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;
use Spatie\Permission\Models\Role;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $name Identifying name of the Project.
 * @property \datetime $open_date The date this Project becomes available.
 * @property \datetime $close_date The date this Project is no longer available.
 * @property string $prompt Instructions given to the user for this Project.
 * @property string $pre_code Code that will be ran in the compiler directly before the user's submitted code.
 *                             The user will be able to use this code in their submission if they desire.
 * @property string $start_code Initial code given to the user to start the Project with.
 * @property string $solution Code defined by the creator of this Project that solves it.
 * @property int $module_id The id of the Module this Project is part of.
 * @property int $previous_lesson_id The id of the lesson within the same Module that needs to be done before this Project can be attempted.
 * @property int $owner_id The id of the user that created this Project.
 * @property int $updated_by The id of the user to last update this Project.
 * @property \datetime $created_at The date the Project was created.
 * @property \datetime $updated_at The date the Project was last updated.
 */
class Project extends Model
{
    // Table Name
    public $table = 'projects';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    /**
     * Returns whether or not the project has teams enabled.
     * Can be returned as a boolean or a string if true is passed-in.
     */
    public function teamsEnabled($asString = false)
    {
        if($this->teams_enabled == true){
            $value = true;
        } else {
            $value = false;
        }

        if($asString){
            if($value){
                $value = 'True';
            } else {
                $value = 'False';
            }
        }

        return $value;
    }

    /**
     * Relationship function
     * Returns the module this project belongs to
     */
    public function module()
    {
        return $this->belongsTo('App\Module');
    }

    /**
     * Relationship function
     * Returns the user this project belongs to
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team', 'project_teams');
    }

    /**
     * Returns the project this course is in if it exists. If not, returns null.
     */
    public function course()
    {
        $module = $this->module;

        if(!empty($module)){
            $concept = $module->concept;
        } else {
            return null;
        }

        if(!empty($concept)){
            $course = $concept->course;
        } else {
            return null;
        }

        if(!empty($course)){
            return $course;
        } else {
            return null;
        }
    }

    public function deepCopy()
    {
        $new_project = new Project();
        $new_project->name = $this->name;
        $new_project->open_date = $this->open_date;
        $new_project->close_date = $this->close_date;
        $new_project->prompt = $this->prompt;
        $new_project->pre_code = $this->pre_code;
        $new_project->start_code = $this->start_code;

        return $new_project;
    }

    /**
     * Returns the projects open date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function getOpenDate($format = 'm/d/Y h:i a')
    {
        return date_format(DateTime::createFromFormat(config("app.dateformat"), $this->open_date), $format);
    }

    /**
     * Returns the projects close date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function getCloseDate($format = 'm/d/Y h:i a')
    {
        return date_format(DateTime::createFromFormat(config("app.dateformat"), $this->close_date), $format);
    }

    public function assignTeams($teams)
    {
        $this->teams()->sync($teams);
    }

    /**
     * Returns the role a user has within a course the project belongs to
     */
    public function getRole($user_id = null)
    {
        if(is_null($user_id)){
            $user_id = auth()->user()->id;
        }

        $course_id = $this->course()->id;

        $ret = DB::table('course_user')->where('course_id', $course_id)->where('user_id', $user_id)->select('role_id')->first();
        
        if(empty($ret) or is_null($ret)){
            return null;
        }
        
        $role_id = $ret->role_id;
        $role = Role::find($role_id);

        return $role;
    }
}
