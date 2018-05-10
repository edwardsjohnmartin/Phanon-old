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
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::paginate(5);
        return view('courses.index')->with('courses', $courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modules = Module::all();
        return view('courses.create')->with('modules', $modules);
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

        //TODO: This needs to be able to save relationships between a course and modules
        // if(count($request->input('modules') > 0)){
        //     foreach($request->input('modules') as $module_id){
        //         $module = Module::find(intval($module_id));
        //         $course->modules()->save($module);
        //     }
        // }
        
        return redirect(url('/courses'))->with('success', 'Course Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::find($id);
        return view('courses.show')->with('course', $course);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::find($id);

        // Check for correct user
        if(auth()->user()->id != $course->user_id){
            return redirect(url('/courses'))->with('error', 'Unauthorized Page');
        }

        return view('courses.edit')->with('course', $course);
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
            'name' => 'required|unique:courses'
        ]);

        $course = Course::find($id);
        $course->name = $request->input('name');

        $course->save();

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
        $course = Course::find($id);

        // Check for correct user
        if(auth()->user()->id != $course->user_id){
            return redirect('/courses')->with('error', 'Unauthorized Page');
        }

        $course->delete();
        return redirect('/courses')->with('success', 'Course Deleted');
    }
}
