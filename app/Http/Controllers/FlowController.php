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
use Carbon;

class FlowController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function create()
    {
        return view('flow.authoring.create');
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
}
