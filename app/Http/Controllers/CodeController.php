<?php
namespace App\Http\Controllers;
use Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Enums\Roles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Lesson;
use App\Module;
use App\Exercise;
use App\Project;
use App\ExerciseProgress;
use DB;
class CodeController extends Controller
{
    public function __construct()
    {
        // isAdmin middleware lets only users with a specific permission to access these resources
        //$this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('codearea.index');
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

    /**
     * Displays a test page to test python code.
     *
     * @return \Illuminate\Http\Response
     */
    public function exercise($id,$eid)
    {
        $myModule = Module::find($id);

        //HACK: This is only to be able to create a way to autocomplete exercises
        $users = $myModule->concept->course->users;

        //HACK: This is going to be used to view exercise completion for a given module.
        // It will be put into a better place and done better.
        $module_completion = array();
        foreach($myModule->lessons() as $lesson){
            $les_arr = array();
            foreach($lesson->exercises() as $exercise){
                $cur_ex_progress = ExerciseProgress::where('user_id', auth()->user()->id)->where('exercise_id', $exercise->id)->first();;       
                
                if(!empty($cur_ex_progress)){
                    $ex_arr = $cur_ex_progress->completed();
                } else {
                    $ex_arr = 0;
                }

                $les_arr[$exercise->id] = $ex_arr;
            }

            $module_completion[$lesson->id] = $les_arr;
        }

        if(isset($eid)){
            $exerciseId = $eid;
        }else{
            $exerciseId = $myModule->currentExercise(1);
        }

        $exercise = Exercise::find($exerciseId);
        return view('codearea.module')
            ->with('module', $myModule)
            ->with('exercise', $exercise)
            ->with('users', $users)
            ->with('module_completion', $module_completion);
    }

    public function project($id)
    {
        $myProject = Project::find($id);
        
        return view('codearea.project',['project' => $myProject]);
    }

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

    public function newexercise($exercise_id)
    {
        $exercise = Exercise::find($exercise_id);

        if(empty($exercise)){
            return redirect('/')->
                with('error', 'That exercise does not exist');
        }

        return view('codearea2.exerciseEditor')->
            with('exercise', $exercise);
    }
}
