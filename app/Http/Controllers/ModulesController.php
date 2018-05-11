<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Module;
use App\Lesson;

use DB;

class ModulesController extends Controller
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
        $modules = Module::paginate(5);
        return view('modules.index')->with('modules', $modules);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lessons = Lesson::all();
        return view('modules.create')->with('lessons', $lessons);
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
            'name' => 'required|unique:modules',
            'open_date' => 'required',
            'open_time' => 'required',
            'close_date' => 'required',
            'close_time' => 'required'
        ]);

        $module = new Module();
        $module->name = $request->input('name');
        $module->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $module->close_date = $request->input('close_date') . ' ' . $request->input('close_time');

        $module->save();

        if(count($request->input('lessons')) > 0){
            $lessons = array();
            foreach($request->input('lessons') as $lesson_id){
                array_push($lessons, $lesson_id);
            }
            $module->lessons()->sync($lessons);
        }

        return redirect('/modules')->with('success', 'Module Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $module = Module::find($id);
        $lessons = $module->lessons;

        return view('modules.show')->with('module', $module)->with('lessons', $lessons);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module = Module::find($id);
        $lessons = Lesson::all();
        $module_lessons = $module->lessons;

        $module_lesson_ids = array();
        foreach($module_lessons as $lesson){
            array_push($module_lesson_ids, $lesson->id);
        }

        return view('modules.edit')->
            with('module', $module)->
            with('lessons', $lessons)->
            with('module_lesson_ids', $module_lesson_ids);
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
            'open_date' => 'required',
            'close_date' => 'required'
        ]);

        $module = Module::find($id);
        $module->name = $request->input('name');
        $module->open_date = $request->input('open_date');
        $module->close_date = $request->input('close_date');

        $module->save();

        return redirect('/modules')->with('success', 'Module Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $module = Module::find($id);

        $module->delete();
        return redirect('/modules')->with('success', 'Module Deleted');
    }
}
