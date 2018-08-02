<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\ProjectSurveyResponse;
use Carbon;

class ProjectSurveyResponsesController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth', 'clearance']);
    }

    public function createResponse(Request $request)
    {
        // Parse the AJAX request
        $all = $request->all();
        $project_id = $all['project_id'];
        $difficulty_rating = $request->all()['difficulty_rating'];
        $enjoyment_rating = $request->all()['enjoyment_rating'];
        $retObject = (object)["success" => false, "errors" => [], "warnings" =>[], "successes" =>[]];

        // Retrieve project if it exists
        $project = Project::find($project_id);

        if(!is_null($project)){
            // Validate user is in course project is in

            // Validate project is still open

            // Validate difficulty rating is within range
            if(!($difficulty_rating >= 0 and $difficulty_rating <= 9)){
                $retObject->errors[] = "The difficulty rating was not within range";
            }

            // Validate enjoyment rating is within range
            if(!($enjoyment_rating >= 0 and $enjoyment_rating <= 9)){
                $retObject->errors[] = "The enjoyment rating was not within range";
            }

            //TODO: Write the logic to save the survey response for every logged in member of a team 

                ProjectSurveyResponse::createResponse($project->id, auth()->user()->id, $difficulty_rating, $enjoyment_rating);
                $retObject->successes[] = "Project Ratings saved for ".auth()->user()->name;
            if($project->teams_enabled &&  session()->exists('members')){
                $members = session('members');
                    foreach ($members as $member){
                        ProjectSurveyResponse::createResponse($project->id, $member->id, $difficulty_rating, $enjoyment_rating);
                $retObject->successes[] = "Project Ratings saved for ".$member->name;
                $retObject->success = true;
                    }
            }
        } else {
            $retObject->errors[] = "Project does not exist";
        }
        return response()->json($retObject);
    }
}
