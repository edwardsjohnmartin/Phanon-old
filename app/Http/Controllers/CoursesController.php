<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Course;
use App\Module;

use DB;

class CoursesController extends Controller
{
    public function __construct(){
        //$this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::paginate(10);
        return view('courses.index')->
            with('courses', $courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $used_modules = Module::where('user_id', auth()->user()->id)->whereNotNull('course_id')->get();
        $unused_modules = Module::where('user_id', auth()->user()->id)->whereNull('course_id')->get();

        $other_modules = Module::where('user_id', '!=', auth()->user()->id)->get();

        return view('courses.create')->
            with('used_modules', $used_modules)->
            with('unused_modules', $unused_modules)->
            with('other_modules', $other_modules);
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
            'name' => 'required|unique:courses'
        ]);

        // Create Course
        $course = new Course();
        $course->name = $request->input('name');
        $course->user_id = auth()->user()->id;

        $course->save();

        if(count($request->input('unused_modules')) > 0){
            $modules = array();
            foreach($request->input('unused_modules') as $module_id){
                array_push($modules, Module::find($module_id));
            }
            $course->modules()->saveMany($modules);
        }
        
        return redirect(url('/courses'))->
            with('success', 'Course Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        $modules = $course->modules;

        return view('courses.show')->
            with('course', $course)->
            with('modules', $modules);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);

        $used_modules = Module::whereNotNull('course_id')->get();
        $unused_modules = Module::whereNull('course_id')->get();

        $course_module_ids = array();
        foreach($course->modules as $module){
            array_push($course_module_ids, $module->id);
        }

        // Check for correct user
        if(auth()->user()->id != $course->user_id){
            return redirect(url('/courses'))->with('error', 'Unauthorized Page');
        }

        return view('courses.edit')->
            with('course', $course)->
            with('used_modules', $used_modules)->
            with('unused_modules', $unused_modules)->
            with('course_module_ids', $course_module_ids);
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
            'name' => 'required'
        ]);

        $course = Course::findOrFail($id);
        
        $course->name = $request->input('name');

        $course->save();

        if(count($request->input('unused_modules')) > 0){
            $modules = array();
            foreach($request->input('unused_modules') as $module_id){
                array_push($modules, Module::find($module_id));
            }
            $course->modules()->saveMany($modules);
        }

        return redirect('/courses')->with('success', 'Course Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // Check for correct user
        if(auth()->user()->id != $course->user_id){
            return redirect('/courses')->with('error', 'Unauthorized Page');
        }

        $course->delete();
        return redirect('/courses')->with('success', 'Course Deleted');
    }

    public function fullview($id) {
        $course = Course::find($id);
        
        return view('courses.fullview')->
            with('course', $course);
    }
}
