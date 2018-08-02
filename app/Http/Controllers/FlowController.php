<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;
use App\Concept;
use App\Module;
use App\Lesson;
use App\Exercise;
use App\Project;
use App\Enums\Roles;
use DB;
use Session;
use Carbon;

class FlowController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth']);
    }

    /**
     * Creates a new course in the database using default values and redirects to the flow page.
     */
    public function create()
    {
        $course = new Course();
        $course->name = "New Course";
        $course->open_date = Carbon\Carbon::now();
        $course->close_date = Carbon\Carbon::now();
        $course->owner_id = auth()->user()->id;
        $course->save();

        return redirect(url('flow/' . $course->id));
    }

    /**
     * Takes in a course id and eager loads a course to show the course flow of.
     */
    public function show($id)
    {
        //$course = Course::where('id', $id)->
        //select('id', 'name', 'open_date', 'close_date', 'owner_id')->
        //with(['unorderedConcepts' => function($concepts){
        //    $concepts->select('id', 'name', 'course_id', 'previous_concept_id')->
        //    with(['unorderedModules' => function($modules){
        //        $modules->select('id', 'name', 'concept_id', 'previous_module_id', 'open_date')->
        //        with(['components' => function($components){
        //            $components->orderBy('previous_lesson_id')->orderBy('type_ordering');
        //        }]);
        //    }]);
        //}])->first();

        // $course = Course::where('id', $id)->
        //  select('id', 'name', 'open_date', 'close_date', 'owner_id')->
        //  with(['unorderedConcepts' => function($concepts){
        //      $concepts->select('id', 'name', 'course_id', 'previous_concept_id')->
        //      with(['unorderedModules' => function($modules){
        //          $modules->select('id', 'name', 'concept_id', 'previous_module_id', 'open_date')->
        //          with(['unorderedLessons' => function($lessons){
        //              $lessons->with('unorderedExercises');
        //          },'unorderedProjects']);
        //      }]);
        //  }])->first();

        $course = Course::getCourse($id);

        $eagered = false;
        if (isset($_GET['eager'])) 
            $eagered = $_GET['eager'] == 'true';

        if(empty($course) or is_null($course)){
            return redirect('/dashboard')->
                with('error', 'That course does not exist');
        }

        // Get users role within the course
        $role = $course->getUsersRole(auth()->user()->id);
        //$progress = $course->ExerciseProgress(auth()->user()->id);

        return view('flow.index')->
            with('course', $course)->
            with('role', $role)->
            with('eagered',$eagered);
    }

    /**
     * Creates a concept in the database using an AJAX request.
     */
    public function createConcept(Request $request)
    {
        $course_id = $request->all()['course_id'];

        $course = Course::find($course_id);
        $concepts = $course->concepts();

        if(count($concepts) > 0){
            $last_concept_id = end($concepts)->id;
        } else {
            $last_concept_id = null;
        }

        $concept = new Concept();
        $concept->name = "New Concept";
        $concept->course_id = $course_id;
        $concept->previous_concept_id = $last_concept_id;
        $concept->owner_id = auth()->user()->id;
        $concept->save();

        return view('flow.concept')->
            with('concept', $concept)->
            with('ajaxCreation', true);
    }

    /**
     * Creates a module in the database using an AJAX request.
     */
    public function createModule(Request $request)
    {
        $concept_id = $request->all()['concept_id'];

        $concept = Concept::find($concept_id);
        $modules = $concept->modules();

        if(count($modules) > 0){
            $last_module_id = end($modules)->id;
        } else {
            $last_module_id = null;
        }

        $module = new Module();
        $module->name = "New Module";
        $module->open_date = Carbon\Carbon::now();
        $module->concept_id = $concept_id;
        $module->previous_module_id = $last_module_id;
        $module->owner_id = auth()->user()->id;
        $module->save();

        return view('flow.module')->
            with('module', $module)->
            with('ajaxCreation', true);
    }

    /**
     * Creates a lesson in the database using an AJAX request.
     */
    public function createLesson(Request $request)
    {
        $module_id = $request->all()['module_id'];

        $module = Module::find($module_id);
        $components = $module->lessonsAndProjects();

        if(count($components) > 0){
            $last_component = end($components);
            if(get_class($last_component) == "App\Lesson"){
                $last_lesson_id = $last_component->id;
            } else {
                $last_lesson_id = $last_component->previous_lesson_id;
            }
        } else {
            $last_lesson_id = null;
        }

        // Create a lesson in the database
        $lesson = new Lesson();
        $lesson->name = "New Lesson";
        $lesson->module_id = $module_id;
        $lesson->previous_lesson_id = $last_lesson_id;
        $lesson->owner_id = auth()->user()->id;
        $lesson->save();

        // Create an exercise in the database to put into the lesson
        $exercise = new Exercise();
        $exercise->prompt = "Empty Prompt";
        $exercise->test_code = "";
        $exercise->lesson_id = $lesson->id;
        $exercise->owner_id = auth()->user()->id;
        $exercise->save();

        return view('flow.lesson')->
            with('lesson', $lesson);
    }

    /**
     * Creates a project in the database using an AJAX request.
     */
    public function createProject(Request $request)
    {
        $module_id = $request->all()['module_id'];

        $module = Module::find($module_id);
        $components = $module->lessonsAndProjects();

        if(count($components) > 0){
            $last_component = end($components);
            if(get_class($last_component) == "App\Lesson"){
                $last_lesson_id = $last_component->id;
            } else {
                $last_lesson_id = $last_component->previous_lesson_id;
            }
        } else {
            $last_lesson_id = null;
        }

        // Create a project in the database
        $project = new Project();
        $project->name = "New Project";
        $project->open_date = Carbon\Carbon::now();
        $project->close_date = Carbon\Carbon::now();
        $project->prompt = "Empty Prompt";
        $project->module_id = $module_id;
        $project->previous_lesson_id = $last_lesson_id;
        $project->owner_id = auth()->user()->id;
        $project->save();

        return view('flow.project')->
            with('project', $project);
    }

    public function editCourse(Request $request)
    {
        $all = $request->all();
        $course_id = $all['course_id'];
        $name = $all['name'];
        $open_date = $all['open_date'];
        $close_date = $all['close_date'];

        // Validate the open_date is valid
        if(strtotime($open_date) === false){
            return "The open date could not be parsed to a usable date";
        } else {
            $open_date = Carbon\Carbon::parse($open_date);
        }

        // Validate the close_date is valid
        if(strtotime($close_date) === false){
            return "The close date could not be parsed to a usable date";
        } else {
            $close_date = Carbon\Carbon::parse($close_date);
        }

        // Validate open_date comes before close_date
        if($open_date > $close_date){
            return "The course open date must come before the course close date";
        }

        // Find existing course from the course_id
        $course = Course::find($course_id);

        // Validate the course does exist
        if(is_null($course) or empty($course)){
            return "Course Not Found";
        }

        // Modify the course details using the data from the AJAX call
        $course->name = $name;
        $course->open_date = $open_date;
        $course->close_date = $close_date;

        // Save the course to the database
        $course->save();

        return "Course Edited Successfully";
    }
}
