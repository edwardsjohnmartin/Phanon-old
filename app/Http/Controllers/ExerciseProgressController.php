<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exercise;
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
            if($exProgress->completed()){
                $error = "That exercise is already complete for that user";
            } else {
                $exProgress->completion_date = Carbon\Carbon::now();
                $exProgress->save();

                $success = "The exercise has been marked as completed";
            }
        }

        return redirect()->route('dashboard')
            ->with('success', $success)
            ->with('error', $error);
    }

    /**
     * Parses the exercise details from the Request object and saves the Exercise to the database
     */
    public function save(Request $request)
    {
        //TODO:: Check into the order of things here.
        // Is checking $request->ajax nessecary?
        // Should that check be done before trying to extract the contents of $request->all()?

        $user_id = auth()->user()->id;
        $all = $request->all();
        $contents = $all['contents'];
        $exercise_id = $all['exercise_id'];
        $success = ($all['success'] == "true");

        // Get the ExerciseProgress for this user and exercise
        $exProgress = ExerciseProgress::where('user_id', $user_id)->where('exercise_id', $exercise_id)->first();

        // Create new ExerciseProgress object if one doesn't exist
        if(empty($exProgress) or is_null($exProgress)){
            $exProgress = new ExerciseProgress();
            $exProgress->user_id = $user_id;
            $exProgress->exercise_id = $exercise_id;
        }

        if($request->ajax()){
            $exProgress->saveProgress($contents, $success);
        }
        $nextExercise = Exercise::where('previous_exercise_id',$exercise_id)->first();

        // response to save.
        $retAnswer = [];
        $retAnswer["success"] = $success;
        $retAnswer["nextId"] = isset($nextExercise)? $nextExercise->id:-1;
        return $retAnswer;
    }

    public function newsave(Request $request)
    {
        $user_id = auth()->user()->id;
        $all = $request->all();
        $contents = $all['contents'];
        if(is_null($contents)){
            $contents = "";
        }
        $exercise_id = $all['exercise_id'];
        $success = ($all['success'] == "true");

        // Get the ExerciseProgress for this user and exercise
        $exProgress = ExerciseProgress::where('user_id', $user_id)->where('exercise_id', $exercise_id)->first();

        // Create new ExerciseProgress object if one doesn't exist
        if(empty($exProgress) or is_null($exProgress)){
            $exProgress = new ExerciseProgress();
            $exProgress->user_id = $user_id;
            $exProgress->exercise_id = $exercise_id;
        }

        if($request->ajax()){
            $exProgress->saveProgress($contents, $success);
        }
    }
}