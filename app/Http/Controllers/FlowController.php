<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;
use App\Concept;
use App\Module;
use App\Lesson;
use App\Project;
use App\Enums\Roles;
use DB;
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
        $course = Course::where('id', $id)->
        select('id', 'name', 'open_date', 'close_date', 'owner_id')->
        with(['unorderedConcepts' => function($concepts){
            $concepts->select('id', 'name', 'course_id', 'previous_concept_id')->
            with(['unorderedModules' => function($modules){
                $modules->select('id', 'name', 'concept_id', 'previous_module_id', 'open_date')->
                with(['components' => function($components){
                    $components->orderBy('previous_lesson_id')->orderBy('type_ordering');
                }]);
            }]);
        }])->first();

        if(empty($course) or is_null($course)){
            return redirect('/dashboard')->
                with('error', 'That course does not exist');
        }

        // Get users role within the course
        $role = $course->getUsersRole(auth()->user()->id);

        return view('flow.index')->
            with('course', $course)->
            with('role', $role);
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
            with('concept', $concept);
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
            with('module', $module);
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
}
