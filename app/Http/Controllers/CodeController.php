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
     * Displays a test page to test python code.
     *
     * @return \Illuminate\Http\Response
     */
    public function current($id,$eid)
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
    public function review($id)
    {
        $myModule = Module::find($id);
        return view('codearea.review',['module' => $myModule]);
    }
}
