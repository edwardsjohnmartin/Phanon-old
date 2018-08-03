<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exercise;
use App\Lesson;
use DB;

class ExercisesController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth', 'clearance']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exercises = Exercise::paginate(10);

        return view('exercises.index')->
            with('exercises', $exercises);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('exercises.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'prompt' => 'required',
            'test_code' => 'required'
        ]);

        // Create Exercise
        $exercise = new Exercise();
        $exercise->prompt = $request->input('prompt');
        $exercise->pre_code = $request->input('pre_code');
        $exercise->start_code = $request->input('start_code');
        $exercise->test_code = $request->input('test_code');
        $exercise->owner_id = auth()->user()->id;

        // Save exercise to the database
        $exercise->save();

        return redirect(url('/exercises'))->
            with('success', 'Exercise Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exercise = Exercise::find($id);

        return view('exercises.show')->
            with('exercise', $exercise);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exercise = Exercise::find($id);

        // Check for correct user
        if(auth()->user()->id != $exercise->owner_id){
            return redirect(url('/exercises'))->
                with('error', 'Unauthorized Page');
        }

        return view('exercises.edit')->
            with('exercise', $exercise);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'prompt' => 'required',
            'test_code' => 'required'
        ]);

        // Get the exercise to be updated
        $exercise = Exercise::find($id);

        // Update its fields
        $exercise->prompt = $request->input('prompt');
        $exercise->pre_code = $request->input('pre_code');
        $exercise->start_code = $request->input('start_code');
        $exercise->test_code = $request->input('test_code');

        // Save the exercise to the database
        $exercise->save();

        return redirect('/exercises')->
            with('success', 'Exercise Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exercise = Exercise::find($id);

        // Check for correct user
        if(auth()->user()->id != $exercise->owner_id){
            return redirect(url('/exercises'))->
                with('error', 'Unauthorized Page');
        }

        $exercise->delete();
        return redirect(url('/exercises'))->
            with('success', 'Exercise Deleted');
    }

    /**
     * Show the form for deep copying a specific exercise
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cloneMe($id)
    {
        $exercise = Exercise::find($id);

        return view('exercises.clone')->
            with('exercise', $exercise);
    }

    /**
     * Create a deep copy of an exercise
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createClone(Request $request)
    {
        $this->store($request);

        return redirect('/exercises')->
            with('success', 'Exercise Cloned');
    }

    /**
     * Takes in a request from an AJAX call and moves the nodes.
     */
    public function move(Request $request)
    {
        $retObj = (Object)["success" => false, "message" => ""];
        $all = $request->all();
        $exercise_id = $all['exercise_id'];
        $new_previous_exercise_id = $all['previous_id'];

        //TODO:Move the nodes.
        if($new_previous_exercise_id == "-1" || $new_previous_exercise_id == ""){
            $new_previous_exercise_id = null;
        }

        // get needed objects
        //   A -- B -- C -- D -- E
        //   A -- D -- B -- C -- E
        //   nP - c - nN -- oP -- oN
        $current = Exercise::find($exercise_id);
        $new_next = Exercise::where(["previous_exercise_id"=>$new_previous_exercise_id,
                                     "lesson_id"=>$current->lesson_id])->first();
        $old_next = Exercise::where(["previous_exercise_id"=>$exercise_id,
                                     "lesson_id"=>$current->lesson_id])->first();

        $next_id = null;
        // these must happen in this order.
        if(!is_null($old_next)){
            $old_next->previous_exercise_id = $current->previous_exercise_id;
        }
        if(!is_null($new_next)){
            $current->previous_exercise_id = $new_next->previous_exercise_id;
            $new_next->previous_exercise_id = $current->id;
            $next_id = $new_next->id;
        }else{
            // place at end of list
            $current->previous_exercise_id = $new_previous_exercise_id;
        }

        // save changes
        if(!is_null($new_next)) $new_next->save();
        if(!is_null($old_next)) $old_next->save();
        $current->save();

        if ($new_previous_exercise_id == null)
            $new_previous_exercise_id = "start";

        if ($next_id == null)
            $next_id = "end";

        $retObj->success = true;
        $retObj->message = "Exercise moved. ".$new_previous_exercise_id
            ." > ".$current->id." > ".$next_id;

        return response()->json($retObj);
    }

}
