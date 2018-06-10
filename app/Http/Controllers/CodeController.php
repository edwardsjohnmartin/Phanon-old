<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Lesson;
use App\Module;
use App\Exercise;
class CodeController extends Controller
{
    public function __construct(){
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
        return view('codearea.module',['module' => $myModule,
                                        'exercise'=>$exercise]);
    }
    /**
     * Displays a test page to test python code.
     *
     * @return \Illuminate\Http\Response
     */
    public function work($id,$eid)
    {
        $myModule = Module::find($id);

        if(isset($eid)){
            $exerciseId = $eid;
        }else{
            $exerciseId = $myModule->currentExercise(1);
        }

        $exercise = Exercise::find($exerciseId);
        return view('codearea.module',['module' => $myModule,
                                        'exercise'=>$exercise]);
    }
    //public function review($id)
    //{
    //    $myModule = Module::find($id);
    //    return view('codearea.review',['module' => $myModule,"exercise"=>$myModule->lessons[0]->exercises[0]]);
    //}

    public function review($id,$eid)
    {
        $myModule = Module::find($id);

        if(isset($eid)){
            $exerciseId = $eid;
        }else{
            $exerciseId = $myModule->currentExercise(1);
        }

        $exercise = Exercise::find($exerciseId);
        return view('codearea.review',['module' => $myModule
                                        ,"exercise"=>$exercise]);
    }
}
