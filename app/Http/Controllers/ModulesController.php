<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Module;
use App\Lesson;
use App\Project;

use DB;

class ModulesController extends Controller {
    public function __construct() {
        $this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $modules = Module::paginate(10);
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
        $projects = Project::all();

        return view('modules.create')->
            with('lessons', $lessons)->
            with('projects', $projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
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
        $module->user_id = auth()->user()->id;

        $module->save();

        if(count($request->input('lessons')) > 0) {
            $lessons = array();
            foreach($request->input('lessons') as $lesson_id){
                array_push($lessons, Lesson::find($lesson_id));
            }
            $module->lessons()->saveMany($lessons);
        }

        if(count($request->input('projects')) > 0) {
            $projects = array();
            foreach($request->input('projects') as $project_id){
                array_push($projects, Project::find($project_id));
            }
            $module->projects()->saveMany($projects);
        }

        return redirect('/modules')->
            with('success', 'Module Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
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
    public function edit($id) {
        $module = Module::find($id);
        $lessons = Lesson::all();
        $projects = Project::all();

        // Check for correct user
        if(auth()->user()->id != $module->user_id) {
            return redirect(url('/modules'))->with('error', 'Unauthorized Page');
        }

        $module_lesson_ids = array();
        foreach($module->lessons as $lesson) {
            array_push($module_lesson_ids, $lesson->id);
        }

        $module_project_ids = array();
        foreach($module->projects as $project) {
            array_push($module_project_ids, $project->id);
        }

        return view('modules.edit')->
            with('module', $module)->
            with('lessons', $lessons)->
            with('projects', $projects)->
            with('module_lesson_ids', $module_lesson_ids)->
            with('module_project_ids', $module_project_ids);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
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

        return redirect('/modules')->
            with('success', 'Module Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $module = Module::find($id);

        // Check for correct user
        if(auth()->user()->id != $module->user_id) {
            return redirect(url('/modules'))->with('error', 'Unauthorized Page');
        }

        $module->delete();
        return redirect('/modules')->with('success', 'Module Deleted');
    }
}
