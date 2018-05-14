<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Exercise;
use App\Lesson;

use DB;

class ExercisesController extends Controller
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
        $exercises = Exercise::paginate(5);
        return view('exercises.index')->with('exercises', $exercises);
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
        $exercise->user_id = auth()->user()->id;
        
        $exercise->save();
        
        return redirect(url('/exercises'))->with('success', 'Exercise Created');
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
        return view('exercises.show')->with('exercise', $exercise);
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
        if(auth()->user()->id != $exercise->user_id){
            return redirect(url('/exercises'))->with('error', 'Unauthorized Page');
        }

        return view('exercises.edit')->with('exercise', $exercise);
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

        $exercise = Exercise::find($id);

        $exercise->prompt = $request->input('prompt');
        $exercise->pre_code = $request->input('pre_code');
        $exercise->start_code = $request->input('start_code');
        $exercise->test_code = $request->input('test_code');

        $exercise->save();

        return redirect('/exercises')->with('success', 'Exercise Updated');
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
        if(auth()->user()->id != $exercise->user_id){
            return redirect(url('/exercises'))->with('error', 'Unauthorized Page');
        }

        $exercise->delete();
        return redirect(url('/exercises'))->with('success', 'Exercise Deleted');
    }
}
