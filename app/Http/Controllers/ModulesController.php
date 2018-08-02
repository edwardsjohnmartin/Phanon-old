<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use App\Lesson;
use App\Project;
use DB;

class ModulesController extends Controller 
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
        $modules = Module::paginate(10);

        return view('modules.index')->
            with('modules', $modules);
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
    public function store(Request $request) 
    {
        $this->validate($request, [
            'name' => 'required|unique:modules',
            'open_date' => 'required',
            'open_time' => 'required'
        ]);

        // Create module
        $module = new Module();
        $module->name = $request->input('name');
        $module->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $module->owner_id = auth()->user()->id;

        // Save module to the database
        $module->save();

        // Get the list of lessons and projects that will be attached to the module
        $lesson_and_project_ids = $request->input('lesson_and_project_order');

        // Variable used to keep track of the last lesson that was added to the module
        $previous_lesson_id = null;

        if(!empty($lesson_and_project_ids)){
            // Attach the lessons and projects to the module
            foreach($lesson_and_project_ids as $entry){
                // Determine if the id is for a lesson or project
                // Each id will be in the format "[object_type] [id]"
                // ex. "lesson 1", "project 5", etc....
                $str = explode(" ", $entry);
                $type = $str[0];
                $id = intval($str[1]);

                if($type == "lesson"){
                    // Get the lesson to be added to this module
                    $lesson = Lesson::find($id);

                    // Remove the lesson from the module it may already be in
                    if(!is_null($lesson->module)){
                        $lesson->module->removeLesson($lesson);
                    }
                
                    // Add the lesson to this module and set its previous_lesson_id field
                    $lesson->module_id = $module->id;
                    $lesson->previous_lesson_id = $previous_lesson_id;

                    // Update the previous_lesson_id variable
                    $previous_lesson_id = $lesson->id;

                    // Save the lesson to the database
                    $lesson->save();
                } elseif($type == "project"){
                    // Get the project to be added to this module
                    $project = Project::find($id);

                    // Add the project to this module and set its previous_lesson_id field
                    $project->module_id = $module->id;
                    $project->previous_lesson_id = $previous_lesson_id;

                    // Save the project to the database
                    $project->save();
                }
            }
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
    public function show($id) 
    {
        $module = Module::find($id);
        $lessonsAndProjects = $module->lessonsAndProjects();

        return view('modules.show')->
            with('module', $module)->
            with('lessonsAndProjects', $lessonsAndProjects);
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
        $projects = Project::all();

        // Check for correct user
        if(auth()->user()->id != $module->owner_id) {
            return redirect(url('/modules'))->
                with('error', 'Unauthorized Page');
        }

        // Create an array that contains the ids of the lessons and projects within the module
        $lesson_ids = array();
        foreach($module->lessons() as $lesson) {
            array_push($lesson_ids, $lesson->id);
        }

        $project_ids = array();
        foreach($module->projects() as $project) {
            array_push($project_ids, $project->id);
        }

        return view('modules.edit')->
            with('module', $module)->
            with('lessons', $lessons)->
            with('projects', $projects)->
            with('lesson_ids', $lesson_ids)->
            with('project_ids', $project_ids);
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
            'open_time' => 'required'
        ]);

        // Get the module to be edited
        $module = Module::find($id);

        // Update its fields
        $module->name = $request->input('name');
        $module->open_date = $request->input('open_date') . ' ' . $request->input('open_time');

        // Save updated module to the database
        $module->save();

        // Remove the module_id and and previous_lesson_id field from all lessons and projects that were previously in the module
        foreach($module->lessonsAndProjects() as $item){
            $item->module_id = null;
            $item->previous_lesson_id = null;
            $item->save();
        }

        // Get the list of lessons and projects that will be attached to the module
        $lesson_and_project_ids = $request->input('lesson_and_project_order');

        // Variable used to keep track of the last lesson that was added to the module
        $previous_lesson_id = null;

        if(!empty($lesson_and_project_ids)){
            // Loop through each entry in lesson_and_project_ids array and attach that item to the module
            foreach($lesson_and_project_ids as $entry){
                // Determine if the id is for a lesson or project
                // Each id will be in the format "[object_type] [id]"
                // ex. "lesson 1", "project 5", etc....
                $str = explode(" ", $entry);
                $type = $str[0];
                $id = intval($str[1]);

                if($type == "lesson"){
                    // Get the lesson to be added to this module
                    $lesson = Lesson::find($id);

                    // If the lesson wasn't in this module already, remove it from its module
                    if($lesson->module != null and $lesson->module->id != $module->id){
                        $lesson->module->removeLesson($lesson);
                    }

                    // Add the lesson to this module and set its previous_lesson_id field
                    $lesson->module_id = $module->id;
                    $lesson->previous_lesson_id = $previous_lesson_id;

                    // Update the previous_lesson_id variable
                    $previous_lesson_id = $lesson->id;

                    // Save the lesson to the database
                    $lesson->save();
                } elseif($type == "project"){
                    // Get the project to be added to this module
                    $project = Project::find($id);

                    // Add the project to this module and set its previous_lesson_id field
                    $project->module_id = $module->id;
                    $project->previous_lesson_id = $previous_lesson_id;

                    // Save the project to the database
                    $project->save();
                }
            }
        }

        return redirect('/modules')->
            with('success', 'Module Updated');
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

        // Check that the module belongs to the logged-in user
        if(auth()->user()->id != $module->owner_id) {
            return redirect(url('/modules'))->with('error', 'Unauthorized Page');
        }

        // Delete the module from the database
        $module->delete();

        return redirect('/modules')->with('success', 'Module Deleted');
    }

     /**
     * Show the form for deep copying a specific module
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cloneMe($id)
    {
        // Get the module to be copied
        $module = Module::find($id);

        // Get all lessons and projects that can be added to the module
        $lessons = Lesson::all();
        $projects = Project::all();

        // Create an array of just the lesson ids already in the module
        $module_lesson_ids = array();
        foreach($module->lessons as $lesson) {
            array_push($module_lesson_ids, $lesson->id);
        }

        // Create an array of just the project ids already in the module
        $module_project_ids = array();
        foreach($module->projects as $project) {
            array_push($module_project_ids, $project->id);
        }

        return view('modules.clone')->
            with('module', $module)->
            with('lessons', $lessons)->
            with('projects', $projects)->
            with('module_lesson_ids', $module_lesson_ids)->
            with('module_project_ids', $module_project_ids);
    }

    /**
     * Create a deep copy of an module
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createClone(Request $request)
    {
        $this->store($request);

        return redirect('/modules')->
            with('success', 'Module Cloned');
    }
}
