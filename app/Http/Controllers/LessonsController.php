<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Exercise;
use App\Module;
use DB;

class LessonsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessons = Lesson::paginate(10);
        return view('lessons.index')->
            with('lessons', $lessons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exercises = Exercise::all();
        return view('lessons.create')->with('exercises', $exercises);
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
            'name' => 'required|unique:lessons',
            'exercises' => 'required',
            'open_date' => 'required'
        ]);

        // Get the list of exercise_ids the user wants to include in the lesson
        $input_exercises = $request->input('exercises');

        // Get the order of exercises within the lesson as an array
        $exercise_order = $request->input('orderedExercises');

        // Create Lesson
        $lesson = new Lesson();
        $lesson->name = $request->input('name');
        $lesson->open_date = $request->input('open_date');
        $lesson->user_id = auth()->user()->id;

        // Save lesson to the database
        $lesson->save();
        
        // Attach the exercises to the lesson
        if(!empty($input_exercises)){
            $exercises = Exercise::find($input_exercises);
            $lesson->exercises()->saveMany($exercises);

            // Write the previous_exercise_id field for every exercise in the lesson
            for($i = 0; $i < count($exercise_order); $i++){
                $exercise = Exercise::find($exercise_order[$i]);
                if($i == 0){
                    $exercise->previous_exercise_id = null;
                } else {
                    $exercise->previous_exercise_id = $exercise_order[$i-1];
                }
                $exercise->save();
            }
        }      

        return redirect(url('/lessons'))->
            with('success', 'Lesson Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::find($id);
        $exercises = $lesson->exercises;

        return view('lessons.show')->
            with('lesson', $lesson)->
            with('exercises', $exercises);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lesson = Lesson::find($id);
        $exercises = Exercise::all();
        $lesson_exercises = $lesson->orderedExercises();

        // Check for correct user
        if(auth()->user()->id != $lesson->user_id){
            return redirect(url('/lessons'))->
                with('error', 'Unauthorized Page');
        }

        $lesson_exercise_ids = array();
        foreach($lesson_exercises as $exercise){
            array_push($lesson_exercise_ids, $exercise->id);
        }

        return view('lessons.edit')->
            with('lesson', $lesson)->
            with('exercises', $exercises)->
            with('lesson_exercises', $lesson_exercises)->
            with('lesson_exercise_ids', $lesson_exercise_ids);
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
            'name' => 'required',
            'exercises' => 'required',
            'open_date' => 'required'
        ]);

        // Get the list of exercise_ids the user wants to include in the lesson
        $input_exercises = $request->input('exercises');

        // Get the order of exercises within the lesson as an array
        $exercise_order = $request->input('orderedExercises');

        // Get the lesson to be updated
        $lesson = Lesson::find($id);

        // Update its fields
        $lesson->name = $request->input('name');
        $lesson->open_date = $request->input('open_date');

        // Save the lesson to the database
        $lesson->save();

        // Set the previous_exercise_id field of all the old exercises within the lesson to null
        foreach($lesson->exercises as $exercise){
            $exercise->previous_exercise_id = null;
            $exercise->save();
        }

        // Attach the exercises to the lesson
        if(!empty($input_exercises)){
            $exercises = Exercise::find($input_exercises);
            $lesson->exercises()->saveMany($exercises);

            // Write the previous_exercise_id field for every exercise in the lesson
            for($i = 0; $i < count($exercise_order); $i++){
                $exercise = Exercise::find($exercise_order[$i]);
                if($i == 0){
                    $exercise->previous_exercise_id = null;
                } else {
                    $exercise->previous_exercise_id = $exercise_order[$i-1];
                }
                $exercise->save();
            }
        }

        return redirect(url('/lessons'))->
            with('success', 'Lesson Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lesson = Lesson::find($id);

        // Check for correct user
        if(auth()->user()->id != $lesson->user_id){
            return redirect(url('/lessons'))->
                with('error', 'Unauthorized Page');
        }

        $lesson->delete();
        return redirect(url('/lessons'))->
            with('success', 'Lesson Deleted');
    }

     /**
     * Show the form for deep copying a specific lesson
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function copy($id)
    {
        $lesson = Lesson::find($id);
        $exercises = Exercise::all();

        $lesson_exercise_ids = array();
        foreach($lesson->exercises as $exercise){
            array_push($lesson_exercise_ids, $exercise->id);
        }

        return view('lessons.clone')->
            with('lesson', $lesson)->
            with('exercises', $exercises)->
            with('lesson_exercise_ids', $lesson_exercise_ids);;
    }

    /**
     * Create a deep copy of an lesson
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create_clone(Request $request)
    {
        $this->store($request);

        return redirect('/lessons')->
            with('success', 'Lesson Cloned');
    }
}
