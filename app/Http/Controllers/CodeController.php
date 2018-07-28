<?php
namespace App\Http\Controllers;

use Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Enums\Roles;
use App\Enums\Permissions;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Lesson;
use App\Module;
use App\Exercise;
use App\Project;
use App\ExerciseProgress;
use App\ProjectProgress;
use DB;

class CodeController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth', 'clearance'])->except(['sandbox']);
    }

    /**
     * Displays a test page to test python code.
     *
     * @return \Illuminate\Http\Response
     */
    public function sandbox()
    {
        return view('codearea.sandbox');
    }

    /**
     * 
     */
    public function exercise($exercise_id = null)
    {
        $exercise = Exercise::find($exercise_id);

        if(is_null($exercise_id) or empty($exercise)){
            return redirect('/')->
                with('error', 'That exercise does not exist');
        }

        // Get users role for the course the project is in
        $role = $exercise->course()->getUsersRole(auth()->user()->id);

        // Get the users latest submission for this exercise
        $exerciseProgress = ExerciseProgress::where('user_id', auth()->user()->id)->where('exercise_id', $exercise_id)->first();

        return view('codearea.exerciseEditor')->
            with('exercise', $exercise)->
            with('exerciseProgress', $exerciseProgress)->
            with('role', $role);
    }

    /**
     * Takes in a request from an AJAX call and edits an exercise in the database.
     */
    public function editExercise(Request $request)
    {
        $all = $request->all();
        $exercise_id = $all['exercise_id'];
        $prompt = $all['prompt'];
        $pre_code = $all['pre_code'];
        $test_code = $all['test_code'];

        // Retrieve exercise from id
        $exercise = Exercise::find($exercise_id);

        // Validate exercise exists
        if(is_null($exercise) or empty($exercise)){
            return "Exercise does not exist.";
        }

        // Edit the exercises details
        $exercise->prompt = $prompt;
        $exercise->pre_code = $pre_code;
        $exercise->test_code = $test_code;
        $exercise->updated_by = auth()->user()->id;

        // Save exercise to the database
        $exercise->save();

        return "The exercise was edited succesfully";
    }
    
    /**
     * Gets a project as well as the logged-in users progress on that project and directs them to the coding page for it. 
     */
    public function project($project_id = null)
    {
        $project = Project::find($project_id);

        // Validate project exists
        if(is_null($project_id) or empty($project)){
            return redirect('/')->
                with('error', 'That project does not exist');
        }

        // Validate the user has permission to access the project
        $course = $project->course();

        $isInCourse = $course->isUserEnrolledOrOwner(auth()->user()->id);

        if($isInCourse){
            $canViewProject = true;

            // Get users role for the course the project is in
            $role = $course->getUsersRole(auth()->user()->id);
        } else {
            $canViewProject = false;

            // Check if user has project edit permission
            foreach(auth()->user()->roles as $userRole){
                if($userRole->hasPermissionTo(Permissions::PROJECT_EDIT)){
                    $canViewProject = true;

                    // If user has the ta role, they can view projects for courses they aren't a ta of, but not edit them
                    //HACK: Hard coding the user as a Student role so they can view but not edit the project
                    $role = Role::where('name', Roles::STUDENT)->first();
                    break;
                }
            }
        }
        
        if(!$canViewProject){
            return redirect('/')->
                with('error', 'You do not have permission to view that project');
        }

        // Check that the current date is within the projects open and close dates. 
        $now = date(config('app.dateformat'));

        // This is going to be commented out for now for testing purposes but it does work and will be used in release
        if($now < $project->open_date){
            //return redirect('/flow/' . $course_id)->
                //with('error', 'That project is not open yet.');
        }

        //TODO: Add a check to see if the project is past due. The project code page should have some kind of flag that dictates whether or not it can save.

        // Get the users latest submission for this project
        $projectProgress = ProjectProgress::where('user_id', auth()->user()->id)->where('project_id', $project_id)->orderBy('last_run_date', 'desc')->first();

        $team = auth()->user()->teamForProject($project->id);

        return view('codearea.projectEditor')->
            with('project', $project)->
            with('projectProgress', $projectProgress)->
            with('role', $role)->
            with('team', $team);
    }

    /**
     * Takes in a request from an AJAX call and edits an project in the database.
     */
    public function editProject(Request $request)
    {
        $all = $request->all();
        $project_id = $all['project_id'];
        $name = $all['name'];
        $prompt = $all['prompt'];
        $pre_code = $all['pre_code'];
        $open_date = $all['open_date'];
        $close_date = $all['close_date'];
        $teams_enabled = $all['teams_enabled'];
        if($teams_enabled == "true"){
            $teams_enabled = 1;
        } else {
            $teams_enabled = 0;
        }

        // Retrieve project from id
        $project = Project::find($project_id);

        // Validate project exists
        if(is_null($project) or empty($project)){
            return "Project does not exist.";
        }

        // Edit the projects details
        $project->name = $name;
        $project->prompt = $prompt;
        $project->pre_code = $pre_code;
        $project->open_date = $open_date;
        $project->close_date = $close_date;
        $project->teams_enabled = $teams_enabled;
        $project->updated_by = auth()->user()->id;

        // Save project to the database
        $project->save();

        return "SUCCESS";
    }

    /**
     * Figure out which is the current module and exercise the user is on
     *   then direct them to the correct coding page.
     *
     * @return \Illuminate\Http\Response
     */
    public function current()
    {
        $myModule = Module::find($id);
        if(isset($eid)){
            $exerciseId = $eid;
        }else{
            $exerciseId = $myModule->currentExercise(1);
        }
        $exercise = Exercise::find($exerciseId);
        if(true){ // has current exercise
            return view('codearea.module',['module' => $myModule,
                                        'exercise'=>$exercise]);
        }else{ // has current project
            return view('codearea.project',['project' => $myModule]);
        }
    }

    // /**
    //  * Displays a test page to test python code.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function exercise($id,$eid)
    // {
    //     $myModule = Module::find($id);

    //     //HACK: This is only to be able to create a way to autocomplete exercises
    //     $users = $myModule->concept->course->users;

    //     //HACK: This is going to be used to view exercise completion for a given module.
    //     // It will be put into a better place and done better.
    //     $module_completion = array();
    //     foreach($myModule->lessons() as $lesson){
    //         $les_arr = array();
    //         foreach($lesson->exercises() as $exercise){
    //             $cur_ex_progress = ExerciseProgress::where('user_id', auth()->user()->id)->where('exercise_id', $exercise->id)->first();;       
                
    //             if(!empty($cur_ex_progress)){
    //                 $ex_arr = $cur_ex_progress->completed();
    //             } else {
    //                 $ex_arr = 0;
    //             }

    //             $les_arr[$exercise->id] = $ex_arr;
    //         }

    //         $module_completion[$lesson->id] = $les_arr;
    //     }

    //     if(isset($eid)){
    //         $exerciseId = $eid;
    //     }else{
    //         $exerciseId = $myModule->currentExercise(1);
    //     }

    //     $exercise = Exercise::find($exerciseId);
    //     return view('codearea.module')
    //         ->with('module', $myModule)
    //         ->with('exercise', $exercise)
    //         ->with('users', $users)
    //         ->with('module_completion', $module_completion);
    // }

    // public function project($id)
    // {
    //     $myProject = Project::find($id);
        
    //     return view('codearea.project',['project' => $myProject]);
    // }

    public function review($id,$eid)
    {
        $myModule = Module::find($id);

        if(isset($eid)){
            $exerciseId = $eid;
        }else{
            $exerciseId = $myModule->currentExercise(1);
        }

        $exercise = Exercise::find($exerciseId);
        return view('codearea.review')->
            with('module', $myModule)->
            with('exercise', $exercise);
    }
}
