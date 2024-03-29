<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Exercise;
use App\Module;
use DB;
use App\Project;

class LessonsController extends Controller
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
        $lessons = Lesson::paginate(10);

        return view('lessons.index')->
            with('lessons', $lessons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exercises = Exercise::all();

        return view('lessons.create')->
            with('exercises', $exercises);
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
            'name' => 'required|unique:lessons',
            'exercises' => 'required'
        ]);

        // Create Lesson
        $lesson = new Lesson();
        $lesson->name = $request->input('name');
        $lesson->owner_id = auth()->user()->id;

        // Save lesson to the database
        $lesson->save();

        // Get the order of exercises within the lesson as an array
        $exercise_order = $request->input('exercise_order');

        // Variable used to keep track of the last exercise added to the lesson
        $previous_exercise_id = null;

        if(!empty($exercise_order)){
            // Attach the exercises to the lesson
            foreach($exercise_order as $id){
                // Get the exercise
                $exercise = Exercise::find($id);

                // Remove the exercises to go into the lesson from any lessons they are currently in
                if($exercise->lesson != null){
                    $exercise->lesson->removeExercise($exercise);
                }

                // Add the exercise to this lesson and set its previous_exercise_id field
                $exercise->lesson_id = $lesson->id;
                $exercise->previous_exercise_id = $previous_exercise_id;

                // Update the previous_exercise_id variable
                $previous_exercise_id = $exercise->id;

                // Save the exercise to the database
                $exercise->save();
            }
        }

        return redirect(url('/lessons'))->
            with('success', 'Lesson Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::find($id);
        $exercises = $lesson->exercises();

        return view('lessons.show')->
            with('lesson', $lesson)->
            with('exercises', $exercises);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lesson = Lesson::find($id);
        $exercises = Exercise::all();
        $lesson_exercises = $lesson->exercises();

        // Check for correct user
        if(auth()->user()->id != $lesson->owner_id){
            return redirect(url('/lessons'))->
                with('error', 'Unauthorized Page');
        }

        // Create an array that contains the ids of the exercises within the lesson
        $lesson_exercise_ids = array();
        foreach($lesson_exercises as $exercise){
            array_push($lesson_exercise_ids, $exercise->id);
        }

        return view('lessons.edit')->
            with('lesson', $lesson)->
            with('exercises', $exercises)->
            with('lesson_exercises', $lesson_exercises)->
            with('lesson_exercise_ids', $lesson_exercise_ids);
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
            'exercises' => 'required'
        ]);

        // Get the lesson to be updated
        $lesson = Lesson::find($id);

        // Update its fields
        $lesson->name = $request->input('name');

        // Save the lesson to the database
        $lesson->save();

        // Remove the lesson_id and previous_exercise_id field from all exercises that were in this lesson
        foreach($lesson->exercises() as $exercise){
            $exercise->lesson_id = null;
            $exercise->previous_exercise_id = null;
            $exercise->save();
        }

        // Get the order of exercises within the lesson as an array
        $exercise_order = $request->input('exercise_order');

        // Variable used to keep track of the last exercise added to the lesson
        $previous_exercise_id = null;

        if(!empty($exercise_order)){
            // Attach the exercises to the lesson
            foreach($exercise_order as $id){
                // Get the exercise
                $exercise = Exercise::find($id);

                // Remove the exercises to go into the lesson from any lessons they are currently in
                if($exercise->lesson != null and $exercise->lesson->id != $lesson->id){
                    $exercise->lesson->removeExercise($exercise);
                }

                // Add the exercise to this lesson and set its previous_exercise_id field
                $exercise->lesson_id = $lesson->id;
                $exercise->previous_exercise_id = $previous_exercise_id;

                // Update the previous_exercise_id variable
                $previous_exercise_id = $exercise->id;

                // Save the exercise to the database
                $exercise->save();
            }
        }

        return redirect(url('/lessons'))->
            with('success', 'Lesson Updated');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function modify(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $retObject = (Object)["type"=>"error","identifier"=>"","message"=>"","html"=>""];
        $all = $request->all();
        $id = $all["lesson_id"];

        // Get the lesson to be updated
        $lesson = Lesson::find($id);

        // Update its fields
        $lesson->name = $request->input('name');

        // Save the lesson to the database
        $lesson->save();
        $retObject->type = "success";
        $retObject->message = "Lesson ".$id." was successfully modified.";
        $retObject->identifier = "lesson_".$lesson->id;
        $role = $lesson->module->concept->course->getUsersRole(auth()->user()->id);
        $retObject->html = view("flow/lesson",['lesson' => $lesson,
         'role' => $role])->render();

        return response()->json($retObject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lesson = Lesson::find($id);

        // Check for correct user
        if(auth()->user()->id != $lesson->owner_id){
            return redirect(url('/lessons'))->
                with('error', 'Unauthorized Page');
        }

        $lesson->delete();
        return redirect(url('/lessons'))->
            with('success', 'Lesson Deleted');
    }

    /**
     * Show the form for deep copying a specific lesson.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cloneMe($id)
    {
        $lesson = Lesson::find($id);
        $exercises = Exercise::all();

        $lesson_exercise_ids = array();
        foreach($lesson->exercises() as $exercise){
            array_push($lesson_exercise_ids, $exercise->id);
        }

        return view('lessons.clone')->
            with('lesson', $lesson)->
            with('exercises', $exercises)->
            with('lesson_exercise_ids', $lesson_exercise_ids);;
    }

    /**
     * Create a deep copy of an lesson.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create_clone(Request $request)
    {
        $this->store($request);

        return redirect('/lessons')->
            with('success', 'Lesson Cloned');
    }

    /**
     * Get the miniEdit form for this lesson
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function miniEditForm($id)
    {
        $lesson = Lesson::find($id);

        return view("lessons.editMini",["lesson"=>$lesson]);
    }

    /**
     * Takes in a request from an AJAX call and moves the nodes.
     */
    public function move(Request $request)
    {
        $retObj = (Object)["success" => false, "message" => ""];


        $all = $request->all();
        $new_previous_component_id = $all['previous_id'];
        $new_previous_component_type = $all['previous_type'];
        $componentToMove_id = $all['current_id'];
        $componentToMove_type = $all['current_type']; // this should always be lesson
        $new_next_component_id = $all['next_id'];
        $new_next_component_type = $all['next_type'];
        $new_module_id = $all['module_id'];

        if($componentToMove_type != "lesson"){
            // wrong type
            // projects should be handled by the project controller.
            $retObj->success = false;
            $retObj->message = "Incorrect type, should be lesson and was ".$componentToMove_type;
        }else{
            if($new_previous_component_id == "-1" || $new_previous_component_id == ""){
                // no previous component, at the start of the list.
                $new_previous_component_id = null;
            }

            if($new_next_component_id == "-1" || $new_next_component_id == ""){
                // no next component, at the end of the list.
                $new_next_component_id = null;
            }



            // get needed objects

            // CurrentLesson -- current component we are moving
            $currentLesson = Lesson::find($componentToMove_id);

            // NewPreviousLesson -- current lesson must point to this; just need the id
            if($new_previous_component_type == "lesson"){
                // easiest; leave as component id; because it is correct
            }else if($new_previous_component_id != null){
                // project; projects cannot be previous, so must get the correct id from the project
                $tempProject = Project::find($new_previous_component_id);
                // now get the real previous lesson id
                $new_previous_component_id = $tempProject->previous_lesson_id;
            }

            // NewNextLesson -- needs to point to the new current
            $new_next = Lesson::where(["previous_lesson_id"=>$new_previous_component_id,
                                         "module_id"=>$new_module_id])->first();

            // OldNextLesson -- needs to point to the old previous lesson (can get from current lesson)
            $old_next = Lesson::where(["previous_lesson_id"=>$componentToMove_id,
                                         "module_id"=>$currentLesson->module_id])->first();

            $next_id = null;
            if(!is_null($new_next) && $currentLesson->id == $new_next->id){
                // lesson cannot be its own previous lesson.
                $retObj->success = false;
                $retObj->message = "Lesson was not moved. ";
            }else{
                // these must happen in this order.

                // OldDependantProjects -- need to point to old previous lesson (can get from current lesson)
                $old_projects = Project::where("previous_lesson_id",$currentLesson->id)->get();
                foreach($old_projects as $proj){
                    $proj->previous_lesson_id = $currentLesson->previous_lesson_id;
                }

                if(!is_null($old_next)){
                    $old_next->previous_lesson_id = $currentLesson->previous_lesson_id;
                }

                // move lesson to new module, if module is the same, nothing will really happen.
                $currentLesson->module_id = $new_module_id;


                if(!is_null($new_next)){
                    // has a next lesson.
                    $currentLesson->previous_lesson_id = $new_next->previous_lesson_id;
                    $new_next->previous_lesson_id = $currentLesson->id;
                    $next_id = $new_next->id;
                }else{
                    // place at end of list
                    $currentLesson->previous_lesson_id = $new_previous_component_id;
                }

                // even if did not have a next lesson, may have next projects.
                $new_projects = [];

                // NewNextProjects -- might or might not exist (need to point to current lesson)
                if($new_next_component_type == "project"){
                    // was moved infront of a project so will make projects originally dependent 
                    // on the new previous, now dependant on this lesson.
                    $new_projects = Project::where("previous_lesson_id",$new_previous_component_id)->get();
                    foreach($new_projects as $proj){
                        $proj->previous_lesson_id = $currentLesson->id;
                    }
                }


                // save changes; all saves at the end to allow rollback
                if(!is_null($new_next)) $new_next->save();
                if(!is_null($old_next)) $old_next->save();
                foreach($old_projects as $proj){
                    $proj->save();
                }
                foreach($new_projects as $proj){
                    $proj->save();
                }
                $currentLesson->save();

                if ($new_previous_component_id == null)
                    $new_previous_component_id = "start";

                if ($next_id == null)
                    $next_id = "end";

                $retObj->success = true;
                $retObj->message = "lesson moved. ".$new_previous_component_id
                    ." > ".$currentLesson->id." > ".$next_id;
            }
        }
        return response()->json($retObj);
    }

}
