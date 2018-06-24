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

    public function save(Request $request)
    {
        $all = $request->all();
        $contents = $all['contents'];
        $exercise_id = $all['exercise_id'];
        $completed = $all['completed'];

        $completed = ($completed == "true");

        if($request->ajax()){

            $message = $this->saveProgress($exercise_id, $contents);

            return response()->json([
                'completed' => $completed, 
                'contents' => $contents, 
                'exercise_id' => $exercise_id,
                'message' => $message
            ]);
        }
    }

    public function saveProgress($exercise_id, $contents)
    {
        // Get logged in user
        $user_id = auth()->user()->id;

        // See if object exists
        $exProgress = ExerciseProgress::where('user_id', $user_id)->where('exercise_id', $exercise_id)->first();
        
        $completed = "existed";

        if(empty($exProgress) or is_null($exProgress)){
            $completed = "didn't exist";

            // Create new ExerciseProgress object
            $exProgress = new ExerciseProgress();
            $exProgress->user_id = $user_id;
            $exProgress->exercise_id = $exercise_id;
            $exProgress->last_contents = $contents;
            $exProgress->last_run_date = Carbon\Carbon::now();
        }

        $x = "initial";
        if($exProgress->completed()){
            $x = " is completed";
        }else{
            $x = " is not completed";
            $exProgress->last_contents = $contents;
            $exProgress->last_run_date = Carbon\Carbon::now();
        }

        $exProgress->save();

        return "this was in saveProgress " . $completed . $x;
    }
}