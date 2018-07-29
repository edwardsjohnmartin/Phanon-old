<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property int $user_id The id of the user this is tracking survey response for.
 * @property int $project_id The id of the Project this is progress for.
 * @property int $difficulty_rating The amount the user rated the difficulty level for this project.
 * @property int $enjoyment_rating The amount the user rated the enjoyment level for this project.
 * @property \datetime $response_date The date the user rated the project.
 */
class ProjectSurveyResponse extends Model
{
    // Table Name
    public $table = 'project_survey_responses';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function createResponse($project_id, $user_id, $difficulty_rating, $enjoyment_rating)
    {
        $projectSurveyResponse = new ProjectSurveyResponse();
        $projectSurveyResponse->project_id = $project_id;
        $projectSurveyResponse->user_id = auth()->user()->id;
        $projectSurveyResponse->difficulty_rating = $difficulty_rating;
        $projectSurveyResponse->enjoyment_rating = $enjoyment_rating;
        $projectSurveyResponse->response_date = Carbon\Carbon::now();
        $projectSurveyResponse->save();
    }
}
