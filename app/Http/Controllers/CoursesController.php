<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;
use App\Concept;
use DB;

class CoursesController extends Controller
{
    public function __construct()
    {
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
        $concepts = Concept::all();

        return view('courses.create')->
            with('concepts', $concepts);
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
            'name' => 'required|unique:courses',
            'concepts' => 'required',
            'open_date' => 'required',
            'open_time' => 'required',
            'close_date' => 'required',
            'close_time' => 'required',            
        ]);

        // Get the list of concept_ids the user wants to include in the course
        $concept_ids = $request->input('concepts');

        // Get the order of concepts within the course as an int array
        $concept_order = $request->input('concept_order');

        // Create course
        $course = new Course();
        $course->name = $request->input('name');
        $course->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $course->close_date = $request->input('close_date') . ' ' . $request->input('close_time');
        $course->user_id = auth()->user()->id;

        // Save course to the database
        $course->save();

        // Attach the concepts to the course
        if(!empty($concept_ids)) {
            $concepts = Concept::find($concept_ids);

            // Remove the concept from the course it is currently in
            foreach($concepts as $concept){
                if($concept->course_id != $course->id){
                    Course::find($concept->course_id)->removeConcept($concept->id);
                }
            }

            $course->unorderedConcepts()->saveMany($concepts);

            // Write the previous_concept_id field for every concept in the course
            for($i = 0; $i < count($concept_order); $i++){
                $concept = Concept::find($concept_order[$i]);
                if($i == 0){
                    $concept->previous_concept_id = null;
                } else {
                    $concept->previous_concept_id = $concept_order[$i-1];
                }
                $concept->save();
            }
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
        $course = Course::find($id);

        return view('courses.show')->
            with('course', $course);
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
        $concepts = Concept::all();

        // Check for correct user
        if(auth()->user()->id != $course->user_id){
            return redirect(url('/courses'))->
                with('error', 'Unauthorized Page');
        }

        // Create an array that contains the ids of the concepts within the course
        $concept_ids = array();
        foreach($course->concepts() as $concept) {
            array_push($concept_ids, $concept->id);
        }

        return view('courses.edit')->
            with('course', $course)->
            with('concepts', $concepts)->
            with('concept_ids', $concept_ids);
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
            'concepts' => 'required',
            'open_date' => 'required',
            'open_time' => 'required',
            'close_date' => 'required',
            'close_time' => 'required',
        ]);

        // Get the list of concept_ids the user wants to include in the course
        $concept_ids = $request->input('concepts');

        // Get the order of concepts within the course as an int array
        $concept_order = $request->input('concept_order');

        // Get the course to be updated
        $course = Course::find($id);
        
        // Update its fields
        $course->name = $request->input('name');
        $course->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $course->close_date = $request->input('close_date') . ' ' . $request->input('close_time');

        // Save the course to the database
        $course->save();

        // Set the course_id and previous_concept_id field of all the old concepts within the course to null
        foreach($course->concepts() as $concept){
            $concept->course_id = null;
            $concept->previous_concept_id = null;
            $concept->save();
        }

        // Attach the concepts to the course
        if(!empty($concept_ids)) {
            $concepts = Concept::find($concept_ids);

            // Remove the concept from the course it is currently in
            foreach($concepts as $concept){
                if($concept->course_id != $course->id){
                    Course::find($concept->course_id)->removeConcept($concept->id);
                }
            }
            
            $course->unorderedConcepts()->saveMany($concepts);

            // Write the previous_concept_id field for every concept in the course
            for($i = 0; $i < count($concept_order); $i++){
                $concept = Concept::find($concept_order[$i]);
                if($i == 0){
                    $concept->previous_concept_id = null;
                } else {
                    $concept->previous_concept_id = $concept_order[$i-1];
                }
                $concept->save();
            }
        }

        return redirect('/courses')->
            with('success', 'Course Updated');
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

        // Check that the course belongs to the logged-in user
        if(auth()->user()->id != $course->user_id){
            return redirect('/courses')->
                with('error', 'Unauthorized Page');
        }

        // Delete the course from the database
        $course->delete();

        return redirect('/courses')->
            with('success', 'Course Deleted');
    }

    /**
     * Display a course and its entire contents on a single page.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function fullview($id) 
    {
        // Retrieve the course to be displayed
        $course = Course::find($id);
        
        return view('courses.fullview')->
            with('course', $course);
    }

    /**
     * Create a deep copy of a specific course and all of its contents
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function copy($id)
    {
        // Get the course to be clone
        $course = Course::find($id);

        // Clone the course
        $new_course = $course->deepCopy();
        $new_course->user_id = auth()->user()->id;

        // Save the copy of the course to the database
        $new_course->save();

        if(!empty($course->modules)){
            $modules = array();

            foreach($course->modules as $module){
                // Clone the module
                $new_module = $module->deepCopy();
                $new_module->user_id = auth()->user()->id;
    
                // Save the copy of the module to the database
                $new_module->save();
    
                if(!empty($module->lessons)){
                    $lessons = array();
    
                    foreach($module->lessons as $lesson){
                        // Clone the lesson
                        $new_lesson = $lesson->deepCopy();
                        $new_lesson->user_id = auth()->user()->id;
        
                        // Save the copy of the lesson to the database
                        $new_lesson->save();
        
                        if(!empty($lesson->exercises)){
                            $exercises = array();
        
                            foreach($lesson->exercises as $exercise){
                                // Clone the exercise
                                $new_exercise = $exercise->deepCopy();
                                $new_exercise->user_id = auth()->user()->id;
            
                                // Save the copy of the exercise to the database
                                $new_exercise->save();
        
                                // Push the new exercise to the exercises array
                                array_push($exercises, $new_exercise);
                            }
        
                            // Attach the new exercises to the new lesson
                            $new_lesson->exercises()->saveMany($exercises);
    
                            // Push the new lesson to the lessons array
                            array_push($lessons, $new_lesson);
                        }
                    }
    
                    // Attach the new lessons to the module
                    $new_module->lessons()->saveMany($lessons);
                }
    
                if(!empty($module->projects)){
                    $projects = array();
    
                    foreach($module->projects as $project){
                        // Clone the project
                        $new_project = $project->deepCopy();
                        $new_project->user_id = auth()->user()->id;
    
                        // Save the copy of the project to the database
                        $new_project->save();
    
                        // Push the new project to the projects array
                        array_push($projects, $new_project);
                    }
    
                    // Attach the new projects to the module
                    $new_module->projects()->saveMany($projects);
                }

                // Push the new module to the modules array
                array_push($modules, $new_module);
            }

            // Attach the new modules to the course
            $new_course->modules()->saveMany($modules);
        }

        return redirect('/courses/' . $new_course->id . '/fullview')->
            with('success', 'Course Cloned');
    }
}
