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

    public static function makeTeam($course_id, $members)
    {
        $team = new Team();
        $team->name = "New Team";
        $team->course_id = $course_id;
        $team->save();

        $team->addMembers($members);

        return $team;
    }

    public function assignProject($project_id)
    {
        $this->projects()->attach($project_id);
    }
}
