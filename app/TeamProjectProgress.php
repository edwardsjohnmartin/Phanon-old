<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property int $team_id The id of the team this is tracking progress for.
 * @property int $project_id The id of the Project this is progress for.
 * @property string $contents The contents of the team's submission the last time the Project was ran.
 * @property \datetime $last_run_date The date of the last time the team ran the Project.
 */
class TeamProjectProgress extends Model
{
    // Table Name
    public $table = 'team_project_progress';
    
    // Primary Key
    public $primaryKey = 'id';

    public function team()
    {
        return $this->belongsTo('App\Team');
    }
}
