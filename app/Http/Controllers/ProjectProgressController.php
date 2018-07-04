<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\ProjectProgress;
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

        // Create a new ProjectProgress object and save to the database
        $projectProgress = new ProjectProgress();
        $projectProgress->saveProgress($project_id, $contents);
    }
}