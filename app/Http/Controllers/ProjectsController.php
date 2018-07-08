<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\Team;
use App\Enums\Roles;
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
        if($request->input('has_partners') == 'yes'){
            $project->has_partners = true;
        } else {
            $project->has_partners = false;
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

        // Check for correct user
        if(auth()->user()->id != $project->owner_id){
            return redirect(url('/projects'))->with('error', 'Unauthorized Page');
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
        if($request->input('has_partners') == 'yes'){
            $project->has_partners = true;
        } else {
            $project->has_partners = false;
        }

        // Save the project to the database
        $project->save();
        
        return redirect(url('/projects'))->
            with('success', 'Project Updated');
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
    public function copy($id)
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
    public function partners($id)
    {
        $project = Project::find($id);

        // Validate project exists
        if(empty($project) or is_null($project)){
            return redirect('/projects')->
                with('error', 'There is no project with an id of ' . $id);
        }

        // Validate user is in the course
        $course = $project->course();
        $users = $course->users()->get()->keyBy('id')->toArray();
        if(!array_key_exists(auth()->user()->id, $users)){
            if(!auth()->user()->isAdmin()){
                return redirect('/')->
                    with('error', 'You are not a listed participant of this course. If this is an error, please contact your course administrator.');
            }
        }

        // Validate course exists
        if(empty($course) or is_null($course)){
            return redirect('/projects/' . $id)->
                with('error', 'This project does not belong to a course and cannot be assigned partners');
        }

        // Get users role within the course
        $role_id = DB::table('course_user')->where('course_id', $course->id)->where('user_id', auth()->user()->id)->select('role_id')->first()->role_id;
        $role = Role::find($role_id);

        return view('projects.partners')->
            with('project', $project)->
            with('course', $course)->
            with('students', $course->getUsersByRole(Roles::id(Roles::STUDENT)))->
            with('role', $role);
    }
}
