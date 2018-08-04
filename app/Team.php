<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $name The identifying string for the team.
 * @property \datetime $created_at The date this team was created.
 * @property \datetime $updated_at The date this team was updated last.
 */
class Team extends Model
{
    // Table Name
    public $table = 'teams';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'project_teams')->withPivot('project_id', 'team_id');
    }

    public function members()
    {
        return $this->belongsToMany('App\User', 'team_users');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function addMember($user_id)
    {
        $this->members()->attach($user_id);
    }

    public function addMembers($members)
    {
        foreach($members as $member){
            $this->addMember($member);
        }
    }

    public static function makeTeam($course_id, $members,$team_name = "New Team")
    {
        $team = new Team();
        $team->name = $team_name;
        $team->course_id = $course_id;
        $team->save();

        $team->addMembers($members);

        return $team;
    }

    public function assignProject($project_id)
    {
        $this->projects()->attach($project_id);
    }

    public static function checkIfTeamExistsInCourse($user_ids, $course_id)
    {
        sort($user_ids);
        
        $teams = Team::where('course_id', $course_id)->get();

        foreach($teams as $team){
            $members = $team->members()->select('id')->withPivot('user_id')->get()->toArray();
        
            $students = [];

            foreach($members as $member){
                array_push($students, $member['id']);
            }

            sort($students);

            if($students == $user_ids){
                return $team;
            }
        }

        return false;
    }
}
