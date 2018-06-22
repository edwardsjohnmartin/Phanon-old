<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;
use App\Concept;
use App\User;
use App\Enums\Roles;
use DB;
use Spatie\Permission\Models\Role;

class CoursesController extends Controller
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

        // Get all users on the website
        $users = User::all();
        $roles = Role::where('name', "!=", Roles::ADMIN)->get(['id', 'name'])->toArray();

        return view('courses.create')->
            with('concepts', $concepts)->
            with('users', $users)->
            with('roles', $roles);
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
            'open_date' => 'required',
            'open_time' => 'required',
            'close_date' => 'required',
            'close_time' => 'required',            
        ]);

        // Create course
        $course = new Course();
        $course->name = $request->input('name');
        $course->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $course->close_date = $request->input('close_date') . ' ' . $request->input('close_time');
        $course->owner_id = auth()->user()->id;

        // Save course to the database
        $course->save();
        
        // Add users to course as specified role
        //TODO: Make sure any user is only in one of the students, tas, and teachers arrays
        $usersToAdd = $request->input('usersToAdd');
        if(count($usersToAdd) > 0){
            foreach($usersToAdd as $entry){
                $data = explode(",", $entry);
                $course->addUserAsRole($data[0], $data[1]);
            }
        }

        // Get the order of concepts within the course as an int array
        $concept_order = $request->input('concept_order');

        // Variable used to keep track of the last concept added to the course
        $previous_concept_id = null;

        if(!empty($concept_order)){
            // Attach the concepts to the course
            foreach($concept_order as $id){
                // Get the concept
                $concept = Concept::find($id);

                // Remove the concepts to go into the course from any courses they are currently in
                if($concept->course != null){
                    $concept->course->removeConcept($concept);
                }

                // Add the concept to this course and set its previous_concept_id field
                $concept->course_id = $course->id;
                $concept->previous_concept_id = $previous_concept_id;

                // Update the previous_concept_id variable
                $previous_concept_id = $concept->id;

                // Save the concept to the database
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
        $course_concepts = $course->concepts();

        // Get all users on the website
        $users = User::all();
        $roles = Role::where('name', "!=", Roles::ADMIN)->get(['id', 'name'])->toArray();

        // Check for correct user
        if($course->owner_id != auth()->user()->id){
            return redirect(url('/courses'))->
                with('error', 'Unauthorized Page');
        }

        // Create an array that contains the ids of the concepts within the course
        $concept_ids = array();
        foreach($course_concepts as $concept) {
            array_push($concept_ids, $concept->id);
        }

        return view('courses.edit')->
            with('course', $course)->
            with('concepts', $concepts)->
            with('course_concepts', $course_concepts)->
            with('concept_ids', $concept_ids)->
            with('users', $users)->
            with('roles', $roles);
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
            'open_time' => 'required',
            'close_date' => 'required',
            'close_time' => 'required',
        ]);

        // Get the course to be updated
        $course = Course::find($id);
        
        // Update its fields
        $course->name = $request->input('name');
        $course->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $course->close_date = $request->input('close_date') . ' ' . $request->input('close_time');

        // Save the course to the database
        $course->save();

        // Add users to course as specified role
        //TODO: Make sure any user is only in one of the students, tas, and teachers arrays
        $usersToAdd = $request->input('usersToAdd');
        if(count($usersToAdd) > 0){
            foreach($usersToAdd as $entry){
                $data = explode(",", $entry);
                $course->addUserAsRole($data[0], $data[1]);
            }
        }

        // Remove the course_id and previous_concept_id from all concepts that were in this course
        foreach($course->concepts() as $concept){
            $concept->course_id = null;
            $concept->previous_concept_id = null;
            $concept->save();
        }

        // Get the order of concepts within the course as an array
        $concept_order = $request->input('concept_order');        

        // Variable used to keep track of the last concept added to the course
        $previous_concept_id = null;

        if(!empty($concept_order)){
            // Attach the concepts to the course
            foreach($concept_order as $id){
                // Get the concept
                $concept = Concept::find($id);

                // Remove the concepts to go into the course from any courses they are currently in
                if($concept->course != null and $concept->course->id != $course->id){
                    $concept->course->removeConcept($concept);
                }

                // Add the concept to this course and set its previous_concept_id field
                $concept->course_id = $course->id;
                $concept->previous_concept_id = $previous_concept_id;

                // Update the previous_concept_id variable
                $previous_concept_id = $concept->id;

                // Save the concept to the database
                $concept->save();
            }
        }

        return redirect('/courses/' . $course->id)->
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
        if(auth()->user()->id != $course->owner_id){
            return redirect('/courses')->
                with('error', 'Unauthorized Page');
        }

        // Delete the course from the database
        $course->delete();

        return redirect('/courses')->
            with('success', 'Course Deleted');
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
        $new_course->owner_id = auth()->user()->id;

        // Save the copy of the course to the database
        $new_course->save();

        if(!empty($course->modules)){
            $modules = array();

            foreach($course->modules as $module){
                // Clone the module
                $new_module = $module->deepCopy();
                $new_module->owner_id = auth()->user()->id;
    
                // Save the copy of the module to the database
                $new_module->save();
    
                if(!empty($module->lessons)){
                    $lessons = array();
    
                    foreach($module->lessons as $lesson){
                        // Clone the lesson
                        $new_lesson = $lesson->deepCopy();
                        $new_lesson->owner_id = auth()->user()->id;
        
                        // Save the copy of the lesson to the database
                        $new_lesson->save();
        
                        if(!empty($lesson->exercises)){
                            $exercises = array();
        
                            foreach($lesson->exercises as $exercise){
                                // Clone the exercise
                                $new_exercise = $exercise->deepCopy();
                                $new_exercise->owner_id = auth()->user()->id;
            
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
                        $new_project->owner_id = auth()->user()->id;
    
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
