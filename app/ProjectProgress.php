<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property int $user_id The id of the user this is tracking progress for.
 * @property int $project_id The id of the Project this is progress for.
 * @property string $contents The contents of the user's submission the last time this Project was ran.
 * @property \datetime $last_run_date The date of the last time the user ran this Project.
 */
class ProjectProgress extends Model
{
    // Table Name
    public $table = 'project_progress';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = false;

    function saveProgress($project_id, $contents)
    {
        $this->user_id = auth()->user()->id;
        $this->project_id = $project_id;
        $this->contents = $contents;
        $this->last_run_date = Carbon\Carbon::now();
        $this->save();
    }
}
