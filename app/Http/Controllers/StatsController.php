<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stats;
use Carbon;

class StatsController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth', 'clearance']);
    }

    public function save(Request $request)
    {
        // Parse request into variables
        $all = $request->all();
        $project_id = $all['project_id'];
        $mouse_clicks = $all['mouse_clicks'];

        //TODO: Validation
        // Check if project exists
        // Check if project is open
        // Check if the user is in the course the project is in

        // Create a stats object and set its attributes with the data passed in through the request
        $newStats = new Stats();
        $newStats->user_id = auth()->user()->id;
        $newStats->project_id = $project_id;
        $newStats->mouse_clicks = $mouse_clicks;
        $newStats->saved_at = Carbon\Carbon::now();
        $newStats->save();

        return "Stats saved successfully";
    }
}
