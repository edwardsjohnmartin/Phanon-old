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
        $this->middleware(['auth']);
    }

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

    public function store(Request $request)
    {
        $all = $request->all();
        $course_details = $all['course'];

        $user_id = auth()->user()->id;

        $course = new Course();
        $course->name = $course_details['name'];
        $course->open_date = $course_details['open_date'];
        $course->close_date = $course_details['close_date'];
        $course->owner_id = $user_id;
        $course->save();

        if(array_key_exists('concepts', $course_details)){
            $previous_concept_id = null;

            foreach($course_details['concepts'] as $arr_concept){
                $concept = new Concept();
                $concept->name = $arr_concept['name'];
                $concept->course_id = $course->id;
                $concept->previous_concept_id = $previous_concept_id;
                $concept->owner_id = $user_id;
                $concept->save();

                if(array_key_exists('modules', $arr_concept)){
                    $previous_module_id = null;

                    foreach($arr_concept['modules'] as $arr_module){
                        $module = new Module();
                        $module->name = $arr_module['name'];
                        $module->open_date = $arr_module['open_date'];
                        $module->concept_id = $concept->id;
                        $module->previous_module_id = $previous_module_id;
                        $module->owner_id = $user_id;
                        $module->save();

                        if(array_key_exists('components', $arr_module)){
                            $previous_lesson_id = null;

                            foreach($arr_module['components'] as $arr_component){
                                if($arr_component['type'] == 'lesson'){
                                    $item = new Lesson();
                                    $item->name = $arr_component['name'];
                                    $item->module_id = $module->id;
                                    $item->previous_lesson_id = $previous_lesson_id;
                                    $item->owner_id = $user_id;
                                    $item->save();

                                    $previous_lesson_id = $item->id;
                                } elseif($arr_component['type'] == 'project'){
                                    $item = new Project();
                                    $item->name = $arr_component['name'];
                                    $item->module_id = $module->id;
                                    $item->previous_lesson_id = $previous_lesson_id;
                                    $item->owner_id = $user_id;

                                    $now = Carbon\Carbon::now();
                                    $item->open_date = $now;
                                    $item->close_date = $now;
                                    $item->prompt = 'Sample Prompt';

                                    $item->save();
                                }
                            }
                        }

                        $previous_module_id = $module->id;
                    }
                }

                $previous_concept_id = $concept->id;
            }
        }

        $course->addUserAsRole($user_id, Roles::id(Roles::TEACHER));

        return view('flow.authoring.store')->
            with('all', $all);

        // return redirect(url('/dashboard'))->
        //     with('success', 'Course Created');
    }

    public function show($id)
    {
        $course = Course::where('id', $id)->
        select('id', 'name', 'open_date', 'close_date')->
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

        return view('flow.index')->
            with('course', $course);
    }

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

        $lesson = new Lesson();
        $lesson->name = "New Lesson";
        $lesson->module_id = $module_id;
        $lesson->previous_lesson_id = $last_lesson_id;
        $lesson->owner_id = auth()->user()->id;
        $lesson->save();

        return view('flow.lesson')->
            with('lesson', $lesson);
    }

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
