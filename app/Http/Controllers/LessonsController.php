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
    public function __construct(){
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessons = Lesson::paginate(5);
        return view('lessons.index')->with('lessons', $lessons);
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

        // Create Lesson
        $lesson = new Lesson();
        $lesson->name = $request->input('name');
        $lesson->open_date = $request->input('open_date');

        $lesson->save();
        
        if(count($request->input('exercises')) > 0){
            $exercises = array();
            foreach($request->input('exercises') as $exercise_id){
                array_push($exercises, $exercise_id);
            }
            $lesson->exercises()->sync($exercises);
        }      

        return redirect(url('/lessons'))->with('success', 'Lesson Created');
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

        return view('lessons.show')->with('lesson', $lesson)->with('exercises', $exercises);
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
        $lesson_exercises = $lesson->exercises;

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

        $lesson = Lesson::find($id);

        $lesson->name = $request->input('name');
        $lesson->open_date = $request->input('open_date');

        $lesson->save();

        if(count($request->input('exercises')) > 0){
            $exercises = array();
            foreach($request->input('exercises') as $exercise_id){
                array_push($exercises, $exercise_id);
            }
            $lesson->exercises()->sync($exercises);
        }

        return redirect(url('/lessons'))->with('success', 'Lesson Updated');
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

        $lesson->delete();
        return redirect(url('/lessons'))->with('success', 'Lesson Deleted');
    }
}
