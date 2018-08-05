<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\Team;
use App\Enums\Roles;
use App\Enums\Permissions;
use Spatie\Permission\Models\Role;
use DB;

class ProjectsController extends Controller
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
        $projects = Project::paginate(10);

        return view('projects.index')->
            with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
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
            'name' => 'required',
            'open_date' => 'required',
            'open_time' => 'required',
            'close_date' => 'required',
            'close_time' => 'required',
            'prompt' => 'required',
        ]);

        // Create Project
        $project = new Project();
        $project->name = $request->input('name');
        $project->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $project->close_date = $request->input('close_date') . ' ' . $request->input('close_time');
        $project->prompt = $request->input('prompt');
        $project->pre_code = $request->input('pre_code');
        $project->start_code = $request->input('start_code');
        if($request->input('teams_enabled') == 'yes'){
            $project->teams_enabled = true;
        } else {
            $project->teams_enabled = false;
        }
        $project->owner_id = auth()->user()->id;

        // Save project to the database
        $project->save();

        return redirect(url('/projects'))->
            with('success', 'Project Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);

        return view('projects.show')->
            with('project', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);

        // Validate the user is in the course the project is in and has adequate permission to edit the project
        $role = $project->getRole();
        if(!is_null($role)){
            if(!$role->hasPermissionTo(Permissions::PROJECT_EDIT) and auth()->user()->id != $project->owner_id){
                return redirect(url('/'))->with('error', 'Unauthorized Page');
            }
        } else {
            return redirect(url('/'))->with('error', 'Unauthorized Page');
        }

        return view('projects.edit')->
            with('project', $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
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
            'prompt' => 'required',
        ]);

        // Get the project to be updated
        $project = Project::find($id);

        // Update its fields
        $project->name = $request->input('name');
        $project->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $project->close_date = $request->input('close_date') . ' ' . $request->input('close_time');
        $project->prompt = $request->input('prompt');
        $project->pre_code = $request->input('pre_code');
        $project->start_code = $request->input('start_code');
        if($request->input('teams_enabled') == 'yes'){
            $project->teams_enabled = true;
        } else {
            $project->teams_enabled = false;
        }

        // Save the project to the database
        $project->save();

        return redirect(url('/projects'))->
            with('success', 'Project Updated');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function modify(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'open_date' => 'required',
            'open_time' => 'required',
            'close_date' => 'required',
            'close_time' => 'required',
        ]);

        $retObject = (Object)["type"=>"error","identifier"=>"","message"=>"","html"=>""];
        $all = $request->all();
        $id = $all["project_id"];

        // Get the project to be updated
        $project = Project::find($id);

        // Update its fields
        $project->name = $request->input('name');
        $project->open_date = $request->input('open_date') . ' ' . $request->input('open_time');
        $project->close_date = $request->input('close_date') . ' ' . $request->input('close_time');
        if($request->input('teams_enabled') == 'yes'){
            $project->teams_enabled = true;
        } else {
            $project->teams_enabled = false;
        }

        // Save the project to the database
        $project->save();
        $retObject->type = "success";
        $retObject->message = "Project ".$id." was successfully modified.";
        $retObject->identifier = "project_".$project->id;
        $role = $project->module->concept->course->getUsersRole(auth()->user()->id);
        $retObject->html = view("flow/project",['project' => $project,
         'role' => $role])->render();

        return response()->json($retObject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        // Check for correct user
        if(auth()->user()->id != $project->owner_id){
            return redirect(url('/projects'))->with('error', 'Unauthorized Page');
        }

        $project->delete();
        return redirect('/projects')->with('success', 'Project Deleted');
    }

    /**
     * Show the form for deep copying a specific project
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cloneMe($id)
    {
        $project = Project::find($id);

        return view('projects.clone')->
            with('project', $project);
    }

    /**
     * Create a deep copy of an project
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createClone(Request $request)
    {
        $this->store($request);

        return redirect('/projects')->
            with('success', 'Project Cloned');
    }

    /**
     *
     */
    public function teams($id)
    {
        $project = Project::find($id);
        $version = request()->query("version");

        // Validate project exists
        if(empty($project) or is_null($project)){
            return redirect('/projects')->
                with('error', 'There is no project with an id of ' . $id);
        }

        // Validate project belongs to a course
        $course = $project->course();
        if(empty($course) or is_null($course)){
            return redirect('/projects/' . $id)->
                with('error', 'This project does not belong to a course and cannot be assigned teams');
        }

        // Validate user is in the course
        $users = $course->users()->get()->keyBy('id')->toArray();
        if(!array_key_exists(auth()->user()->id, $users)){
            if(!auth()->user()->isAdmin()){
                return redirect('/')->
                    with('error', 'You are not a listed participant of this course. If this is an error, please contact your course administrator.');
            }
        }

        // Get users role within the course
        $role = $project->getRole();

        // If the user is a student, find their team for the project
        $isStudent = $role->name == Roles::STUDENT;
        if($isStudent){
            $ret = auth()->user()->teamForProject($project->id);
            if(!is_null($ret)){
                $team = [$ret];
            } else {
                $team = $ret;
            }
        } else {
            $team = null;
        }

        if(!$project->teamsEnabled()){
            if($role->hasPermissionTo(PERMISSIONS::PROJECT_EDIT) or auth()->user()->id == $project->owner_id){
                return redirect(url('projects/' . $project->id . '/edit'))->
                    with('error', 'Teams are not enabled for this project. You can enable them here.');
            } else {
                return redirect(url('/'))->
                    with('error', 'Teams are not enabled for that project.');
            }
        }

        if($version == "modal"){
            return view('projects.teamMembersForm')->
                    with('project', $project)->
                    with('course', $course)->
                    with('students', $course->getUsersByRole(Roles::id(Roles::STUDENT)))->
                    with('role', $role);
        }else{
            return view('projects.teams')->
                    with('project', $project)->
                    with('course', $course)->
                    with('students', $course->getUsersByRole(Roles::id(Roles::STUDENT)))->
                    with('role', $role)->
                    with('team', $team);
        }
    }

    /**
     * Get the miniEdit form for this project
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function miniEditForm($id)
    {
        $project = Project::find($id);

        return view("projects.editMini",["project"=>$project]);
    }

    /**
     * Takes in a request from an AJAX call and moves the nodes.
     */
    public function move(Request $request)
    {
        $retObj = (Object)["success" => false, "message" => ""];

        // this should be much simpler than a lesson. Because we basically just change the previous lesson id.

        $all = $request->all();
        $new_previous_component_id = $all['previous_id'];
        $new_previous_component_type = $all['previous_type'];
        $componentToMove_id = $all['current_id'];
        $componentToMove_type = $all['current_type']; // this should always be lesson
        $new_module_id = $all['module_id'];

        // do not need these even though they are passed. but that is to keep the JavaScript
        // consistent. 
        $new_next_component_id = $all['next_id'];
        $new_next_component_type = $all['next_type'];


        if($componentToMove_type != "project"){
            // wrong type
            // lessons should be handled by the lesson controller.
            $retObj->success = false;
            $retObj->message = "Incorrect type, should be project and was ".$componentToMove_type;
        }else{
            if($new_previous_component_id == "-1" || $new_previous_component_id == ""){
                // no previous component, at the start of the list.
                $new_previous_component_id = null;
            }

            // get needed objects

            // CurrentProject -- current component we are moving
            $current = Project::find($componentToMove_id);

            // NewPreviousLesson -- current lesson must point to this; just need the id
            if($new_previous_component_type == "lesson"){
                // easiest; leave as component id; because it is correct
            }else if($new_previous_component_id != null){
                // project; projects cannot be previous, so must get the correct id from the project
                $tempProject = Project::find($new_previous_component_id);
                // now get the real previous lesson id
                $new_previous_component_id = $tempProject->previous_lesson_id;
            }

            // move project to new module, if module is the same, nothing will really happen.
            $current->module_id = $new_module_id;

            // place after most previous lesson
            $current->previous_lesson_id = $new_previous_component_id;

            //HACK: we may have to worry about dates here for ordering.

            // save changes; all saves at the end to allow rollback
            $current->save();

            $retObj->success = true;
            $retObj->message = "Project moved. ".$new_previous_component_id
                ." > ".$current->id;

        }
        return response()->json($retObj);
    }


}
