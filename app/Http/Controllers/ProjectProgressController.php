<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\ProjectProgress;
use App\Team;
use App\TeamProjectProgress;
use Carbon;

class ProjectProgressController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth', 'clearance']);
    }

    /**
     * Parses the project details from the Request object and saves the Project to the database
     */
    public function save(Request $request)
    {
        // Parse the AJAX request
        $all = $request->all();
        $project_id = $all['project_id'];
        $contents = $request->all()['contents'];

        if(is_null($contents)){
            $contents = "";
        }

        // Retrieve project if it exists
        $project = Project::find($project_id);

        if(!is_null($project)){
            $team_saved = false;

            // If teams are enabled, retrieve team for logged in user for this project
            if($project->teamsEnabled()){
                $team = auth()->user()->teamForProject($project->id);

                // If team existed, check if at least two members of the team are logged in as main user or in session
                if(!is_null($team)){
                    $team_member_ids = [];
                    $session_member_ids = [];
                    array_push($session_member_ids, auth()->user()->id);

                    foreach($team->members as $member){
                        array_push($team_member_ids, $member->id);
                    }

                    // Check which team members are logged in and if there are any users logged in not part of the team
                    if(session()->exists('members') and count(session('members')) > 0){
                        foreach(session('members') as $member){
                            array_push($session_member_ids, $member->id);
                        }
                    }

                    $intersect = array_intersect($team_member_ids, $session_member_ids);

                    // If they are, save the project for the team instead of to the specific user
                    if(count($intersect) >= 2){
                        $team_saved = true;

                        // Create a new TeamProjectProgress object and save to the database
                        $teamProjectProgress = new TeamProjectProgress();
                        $teamProjectProgress->saveProgress($team->id, $project_id, $contents);
                    }
                }
            }
            
            if(!$team_saved) {
                // Create a new ProjectProgress object and save to the database
                $projectProgress = new ProjectProgress();
                $projectProgress->saveProgress($project_id, $contents);
            }
        }
    }
}
