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
use App\Course;
use App\Code;
use App\Choice;
use App\Scale;
use App\ExerciseProgress;
use App\ProjectProgress;
use App\ProjectSurveyResponse;
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

    public function createExercise(Request $request)
    {
        $lesson_id = $request->all()['lesson_id'];
        $exercise_id = $request->all()['exercise_id']; // should only be set if being inserted in list.
        $exercise_count = $request->all()['exercise_count'];
        $type = $request->all()['type'];

        $lesson = Lesson::find($lesson_id);
        if(is_null($lesson) or empty($lesson)){
            return "Cannot create a new exercise in this lesson";
        }

        $exercises = $lesson->exercises();
        $pre_exercise_id = end($exercises)->id; //set to last exercise ID

        // hold next exercise if added to middle of list.
        $next_exercise = null;
        if(!(is_null($exercise_id) && empty($exercise_id)) && $exercise_id > 0){
            $pre_exercise_id = $exercise_id; // set to after specified exercise
            $next_exercise = Exercise::where('previous_exercise_id',$exercise_id)->first();
        }

        // Create an exercise in the database to put into the lesson
        $new_exercise = new Exercise();
        $new_exercise->lesson_id = $lesson->id;
        $new_exercise->previous_exercise_id = $pre_exercise_id;
        $new_exercise->owner_id = auth()->user()->id;
        $new_exercise->save();

        if($type == "code"){
            $code_exercise = new Code();
            $code_exercise->prompt = "Empty Prompt";
            $code_exercise->test_code = "#No Test Code Give";
            $code_exercise->save();
    
            $new_exercise->type()->associate($code_exercise)->save();
        } elseif($type == "choice"){
            $choice_exercise = new Choice();
            $choice_exercise->prompt = "Empty Prompt";
            $choice_exercise->choices = "";
            $choice_exercise->solution = "";
            $choice_exercise->save();
    
            $new_exercise->type()->associate($choice_exercise)->save();
        } elseif($type == "scale"){
            $scale_exercise = new Scale();
            $scale_exercise->prompt = "Empty Prompt";
            $scale_exercise->num_options = 0;
            $scale_exercise->labels = "";
            $scale_exercise->save();
    
            $new_exercise->type()->associate($scale_exercise)->save();
        }
        
        if(!is_null($next_exercise)){
            // If next_exercise is null, place the new exercise at the end of the lesson
            $next_exercise->previous_exercise_id = $new_exercise->id;
            $next_exercise->save();
        }
        
        $role = $lesson->module->concept->course->getUsersRole(auth()->user()->id);

        return view('codearea.exerciseNavItem', [
            'exercise' => $new_exercise,
            'exercise_count' => $exercise_count,
            'is_active' => true,
            'class' => 'new',
            'role' => $role
        ]);
    }

    /**
     * Create a copy of the object related to the given object.
     * @param $request \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function copyExercise(Request $request){

        $lesson_id = $request->all()['lesson_id'];
        $exercise_id = $request->all()['exercise_id'];

        $old_exercise = Exercise::find($exercise_id);
        if(is_null($old_exercise) || empty($old_exercise)){
            return "Cannot create a new exercise in this lesson";
        }
        $next_exercise = Exercise::where('previous_exercise_id',$old_exercise->id)->first();

        $new_exercise = $old_exercise->deepCopy($lesson_id, $old_exercise->id);

        // place new exercise in the correct place in the line.
        if(!(is_null($next_exercise) && empty($next_exercise))){
            // if no next exercise, must have been added to the end.
            $next_exercise->previous_exercise_id = $new_exercise->id;
            $next_exercise->save();
        }
        $role = $old_exercise->lesson->module->concept->course->getUsersRole(auth()->user()->id);
        return view('codearea.exerciseNavItem',['exercise' => $new_exercise,
                                                'exercise_count' => -1,
                                                'is_active' => true,
                                                'class' => 'new',
                                                'role' => $role]);
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
        $start_code = $all['start_code'];
        $solution = $all['solution'];

        // Retrieve exercise from id
        $exercise = Exercise::find($exercise_id);

        // Validate exercise exists
        if(is_null($exercise) or empty($exercise)){
            return "Exercise does not exist.";
        }

        // prompt cannnot be null in DB.
        if(is_null($prompt)){
            $prompt = $exercise->type->prompt;
        } 

        // test code cannnot be null in DB.
        if(is_null($test_code)){
            $test_code = $exercise->type->test_code;
        }

        // Edit the exercises details
        $exercise->updated_by = auth()->user()->id;
        $exercise->save();

        $code_exercise = Code::find($exercise->type->id);
        $code_exercise->prompt = $prompt;
        $code_exercise->pre_code = $pre_code;
        $code_exercise->test_code = $test_code;
        $code_exercise->start_code = $start_code;
        $code_exercise->solution = $solution;
        $code_exercise->save();

        $code_exercise->exercise()->save($exercise);
        $exercise->type()->associate($code_exercise)->save();

        return "The exercise was edited succesfully";
    }

    public function editChoiceExercise(Request $request)
    {
        $all = $request->all();
        $exercise_id = $all['exercise_id'];
        $prompt = $all['prompt'];
        $choices = $all['choices'];
        $solution = $all['solution'];

        // Retrieve exercise from id
        $exercise = Exercise::find($exercise_id);

        // Validate exercise exists
        if(is_null($exercise) or empty($exercise)){
            return "Exercise does not exist.";
        }

        if(is_null($prompt)){
            $prompt = "Empty Prompt";
        }

        if(is_null($solution)){
            $solution = $exercise->type->solution;
        }

        // Edit the exercises details
        $exercise->updated_by = auth()->user()->id;
        $exercise->save();

        $choice_exercise = Choice::find($exercise->type->id);
        $choice_exercise->prompt = $prompt;
        $choice_exercise->choices = json_encode($choices);
        $choice_exercise->solution = $solution;
        $choice_exercise->save();

        $choice_exercise->exercise()->save($exercise);
        $exercise->type()->associate($choice_exercise)->save();

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

        // Get the users latest survey response for this project
        $projectSurveyResponse = ProjectSurveyResponse::where('user_id', auth()->user()->id)->where('project_id', $project_id)->orderBy('response_date', 'desc')->first();

        $team = auth()->user()->teamForProject($project->id);

        return view('codearea.projectEditor')->
            with('project', $project)->
            with('projectProgress', $projectProgress)->
            with('role', $role)->
            with('team', $team)->
            with('projectSurveyResponse', $projectSurveyResponse);
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

    public function module($module_id)
    {
        $module = Module::find($module_id);

        if($module->unorderedLessons->count() == 0){
            return redirect('/flow/' . $module->concept->course->id)->
                with('error', 'That module does not contain any lessons');
        } else {
            $lessons = $module->lessons();
            foreach($lessons as $lesson){
                $l = Lesson::getLessonWithExerciseProgress($lesson->id);
                $exercise = $l->nextIncompleteExercise(false);
                if(!is_null($exercise)){
                    return redirect('/code/exercise/' . $exercise->id);
                }
            }

            if(count($module->lessons()[0]->exercises()) > 0){
                $exercise = $module->lessons()[0]->exercises()[0];
        
                return redirect('/code/exercise/' . $exercise->id);
            } else {
                return redirect('/flow/' . $module->concept->course->id)->
                    with('error', 'There is no suitable exercise to show');
            }
        }
    }

    public function lesson($lesson_id)
    {
        $lesson = Lesson::getLessonWithExerciseProgress($lesson_id);

        $exercise = $lesson->nextIncompleteExercise();

        return redirect('/code/exercise/' . $exercise->id);
    }
}
