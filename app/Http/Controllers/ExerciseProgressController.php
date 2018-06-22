<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ExerciseProgress;
use Carbon;

class ExerciseProgressController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth', 'clearance']);
    }

    public function complete(Request $request, $exercise_id)
    {
        $user_id = $request->input('user');

        $exProgress = ExerciseProgress::where('user_id', $user_id)->where('exercise_id', $exercise_id)->first();

        $success = "";
        $error = "";

        if(empty($exProgress)){
            $exProgress = new ExerciseProgress();
            $exProgress->user_id = $user_id;
            $exProgress->exercise_id = $exercise_id;
            $exProgress->completion_date = Carbon\Carbon::now();
            $exProgress->save();

            $success = "A new progress object was saved for user_id: " . $user_id . " on exercise_id: " . $exercise_id;
        }else{
            $error = "That exercise is already complete for that user";
        }

        return redirect()->route('dashboard')
            ->with('success', $success)
            ->with('error', $error);
    }
}