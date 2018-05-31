<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use DB;

class ProjectsController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Don't check if a user is logged in for the functions in the 'except' array
        $this->middleware('auth', ['except' => ['index', 'show']]);
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
            'name' => 'required|unique:projects',
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
        $project->user_id = auth()->user()->id;

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
        if(auth()->user()->id != $project->user_id){
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
        if(auth()->user()->id != $project->user_id){
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
}
