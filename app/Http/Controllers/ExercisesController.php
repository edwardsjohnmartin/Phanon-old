<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exercise;
use App\Lesson;
use App\Code;
use App\Scale;
use App\Choice;
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
        $types = Exercise::types();

        return view('exercises.create')->
            with('types', $types);
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
            'type' => 'required'
        ]);

        // Create Exercise
        $exercise = new Exercise();
        $exercise->owner_id = auth()->user()->id;

        // Save exercise to the database
        $exercise->save();
        
        if($request->input('type') == 'code'){
            $code_prompt = $request->input('code_prompt');
            if(is_null($code_prompt)){
                $code_prompt = "Empty Prompt";
            }
            $test_code = $request->input('test_code');
            if(is_null($test_code)){
                $test_code = "#No Test Code Given";
            }

            $code_exercise = new Code();
            $code_exercise->prompt = $code_prompt;
            $code_exercise->pre_code = $request->input('pre_code');
            $code_exercise->start_code = $request->input('start_code');
            $code_exercise->test_code = $test_code;
            $code_exercise->solution = $request->input('code_solution');
            $code_exercise->save();

            $exercise->type()->associate($code_exercise)->save();
        } elseif($request->input('type') == 'choice'){
            $choice_prompt = $request->input('choice_prompt');
            if(is_null($choice_prompt)){
                $choice_prompt = "Empty Prompt";
            }

            $choices = "none";
            if(!is_null($request->input('choice_names'))){
                $choices = json_encode($request->input('choice_names'));
            }

            $solution = $request->input('choice_solution');
            if(is_null($solution)){
                $solution = "none";
            }

            $choice_exercise = new Choice();
            $choice_exercise->prompt = $choice_prompt;
            $choice_exercise->choices = $choices;
            $choice_exercise->solution = $solution;
            $choice_exercise->save();
            
            $choice_exercise->exercise()->save($exercise);
            $exercise->type()->associate($choice_exercise)->save();
        } elseif($request->input('type') == 'scale'){
            $scale_prompt = $request->input('scale_prompt');
            if(is_null($scale_prompt)){
                $scale_prompt = "Empty Prompt";
            }

            $num_options = $request->input('num_options');
            
            $labels = "none";
            if(!is_null($request->input('labels'))){
                $labels = json_encode($request->input('labels'));
            }

            $scale_exercise = new Scale();
            $scale_exercise->prompt = $scale_prompt;
            $scale_exercise->num_options = $num_options;
            $scale_exercise->labels = $labels;
            $scale_exercise->save();
            
            $scale_exercise->exercise()->save($exercise);
            $exercise->type()->associate($scale_exercise)->save();
        }

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
        $types = Exercise::types();

        // Check for correct user
        if(auth()->user()->id != $exercise->owner_id){
            return redirect(url('/exercises'))->
                with('error', 'Unauthorized Page');
        }

        return view('exercises.edit')->
            with('exercise', $exercise)->
            with('types', $types);
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
         if(!is_null($new_next) && $current->id == $new_next->id){
            // module cannot be its own previous module.
            $retObj->success = false;
            $retObj->message = "Exercise was not moved. ";
        }else{
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
         }
        return response()->json($retObj);
    }

}
