<?php
namespace App;

use App\ObjectTools;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use DateTime;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $name Identifying name of the Module.
 * @property int $concept_id The id of the Concept this Module is part of.
 * @property int $previous_module_id The id of the Module within the same Concept that needs to be done before this Module can be attempted.
 * @property int $owner_id The id of the user that created this Module.
 * @property int $updated_by The id of the user to last update this Module.
 * @property \datetime $created_at The date the Module was created.
 * @property \datetime $updated_at The date the Module was last updated.
 */
class Module extends Model
{
    // Table Name
    public $table = 'modules';

    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    // tempComponents
    public $tempComponents = [];

    // Toggle whether to use Eager loading
    public $eagerLoading = false;

    /**
     * Relationship function
     * Returns the concept this module belongs to
     */
    public function concept()
    {
        return $this->belongsTo('App\Concept');
    }

    public function components()
    {
        return $this->testLessons()->union($this->testProjects()->toBase());
    }

    /**
     * Relationship function
     * Returns an array of lessons contained in this module
     */
    public function unorderedLessons()
    {
        return $this->hasMany('App\Lesson');
    }

    /**
     * Relationship function
     * Returns an array of projects contained in this module
     */
    public function unorderedProjects()
    {
        return $this->hasMany('App\Project')->orderBy('open_date');
    }

    public function testLessons()
    {
        return $this->hasMany('App\Lesson')->select('id', 'name', 'module_id', 'previous_lesson_id')->addSelect(DB::raw("'lesson' as type"))->addSelect(DB::raw("'2' as type_ordering"));
    }

    public function testProjects()
    {
        return $this->hasMany('App\Project')->select('id', 'name', 'module_id', 'previous_lesson_id')->addSelect(DB::raw("'project' as type"))->addSelect(DB::raw("'1' as type_ordering"));
    }

    /**
     * Relationship function
     * Returns the user this module belongs to
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Return an array of only the lessons in the module in the order they should appear in
     */
    public function lessons()
    {
        // Get the lessons that are in the module
        $lessons = $this->unorderedLessons();

        // Create the array that will store the lessons in the correct order
        $ordered_lessons = array();

        if($this->eagerLoading){
            // this approach should use the eager loaded and not do additional DB calls.
            // saved ~200 ms in the test cases.
            foreach($lessons as $lesson){
                $prevId = $lesson->previous_lesson_id;
                $index = ObjectTools::getIndex($ordered_lessons, $prevId);
                if($index > -1){
                    // add after previous
                    array_splice($ordered_lessons,$index+1,0,[$lesson]);
                }else{
                    // add at end
                    $ordered_lessons[] = $lesson;
                }

            }
        }else{
            // uses lazy loading
            if(count($lessons) > 0){
                //Get the first lesson (its previous_lesson_id is null) and put it into the array
                $lesson = $this->unorderedLessons()->whereNull('previous_lesson_id')->get()[0];
                array_push($ordered_lessons, $lesson);

                $done = false;
                while(!$done){
                    $next_lesson = self::nextLesson($lesson->id);

                    if(!is_null($next_lesson)){
                        $lesson = $next_lesson;

                        array_push($ordered_lessons, $lesson);
                    } else {
                        $done = true;
                    }
                }
            }
        }

        return $ordered_lessons;
    }

    /**
     * Return an array of projects in the module, ordered by open_date
     */
    public function projects()
    {
        return $this->unorderedProjects()->orderBy('open_date')->get();
    }

    /**
     * Returns an array of all lessons and projects contained in the module in their correct order
     */
    public function lessonsAndProjects()
    {
        $ordered_contents = array();

        if($this->eagerLoading){
            // this approach should use the eager loaded and not do additional DB calls.
            // saved ~300 ms in the test cases.
            foreach($this->unorderedLessons as $lesson){
                $prevId = $lesson->previous_lesson_id;
                $index = ObjectTools::getIndex($ordered_contents, $prevId);
                if($index > -1){
                    // add after previous
                    array_splice($ordered_contents,$index+1,0,[$lesson]);
                }else{
                    // add at end
                    $ordered_contents[] = $lesson;
                }
            }
            foreach($this->unorderedProjects as $project){
                $prevId = $project->previous_lesson_id;
                $index = ObjectTools::getIndex($ordered_contents, $prevId);
                if($index > -1){
                    // add after previous
                    array_splice($ordered_contents,$index+1,0,[$project]);
                }else{
                    // add at end
                    $ordered_contents[] = $project;
                }

            }
        }else{
            // uses lazy loading
            // Add any projects that don't come after a lesson
            
            $null_projects = $this->unorderedProjects()->whereNull('previous_lesson_id')->orderBy('open_date', 'ASC')->get();
            if(count($null_projects) > 0){
                foreach($null_projects as $null_project){
                    array_push($ordered_contents, $null_project);
                }
            }

            if(count($this->unorderedLessons) > 0){
                $lessons = $this->lessons();

                // Add all lessons in order of dependance
                foreach($lessons as $lesson){
                    //array_push($ordered_contents, $lesson);
                    $ordered_contents[] = $lesson; // faster

                    // Add the projects that come after each lesson
                    
                    $projects = $this->unorderedProjects()->where('previous_lesson_id', $lesson->id)->orderBy('open_date', 'ASC')->get();
                    foreach($projects as $project){
                        //array_push($ordered_contents, $project);
                        $ordered_contents[] = $project; // faster
                    }
                }
            }
        }
        return $ordered_contents;
    }

    /**
     * Returns the lesson that comes after the given lesson within the module
     */
    public function nextLesson($id)
    {
        $next_lesson = $this->unorderedLessons()->where('previous_lesson_id', $id)->get();

        if(count($next_lesson) > 0){
            return $next_lesson[0];
        } else {
            return null;
        }
    }

    /**
     * Returns the current exercise not done in this module
     */
    public function currentExercise($userID)
    {
        $exerToDo = null;
        //HACK: for now just return the first ID, but will need to get
        // the current exercise.

        // force to date
        //if(!is_a($dateToCheck,'DateTime')){
        //    $dateToCheck = DateTime::createFromFormat('Y-m-d G:i:s',$dateToCheck);
        //}
        //foreach($this->lessons() as $lesson){
        //    foreach($lesson->exercises() as $exer){
        //        if ($exer->getOpenDate())
        //    }
        //}

        //if($this->eagerLoading){
        //    //HACK: this is just to speed things up for now.
        //    $exerToDo = new Exercise();
        //    $exerToDo->id = -1;
        //}else{
            
                $containingLesson = $this->lessons()[0];
                $containingLesson->eagerLoading = $this->eagerLoading;
                if(count($containingLesson->exercises()) > 0){
                    $exerToDo = $containingLesson->exercises()[0];
                } else {
                    $exerToDo = Exercise::find(1);
                }
            
        //}

        return $exerToDo;
    }

    /**
     * Removes the lesson from this modules and retains the ordering of the remaining lessons and projects
     */
    public function removeLesson($lesson)
    {
        $lessons = $this->lessons();

        // This will be the value to change the previous_lesson_id any items that come after the lesson to be deleted
        $value = $lesson->previous_lesson_id;

        // Boolean value to keep track of whether the lesson to be deleted has been found in the module
        // and if a lesson after that (ie. the stopping point) has been found
        $found_this_lesson = false;
        $found_next_lesson = false;

        foreach($this->lessonsAndProjects() as $item){
            if($found_this_lesson and !$found_next_lesson){
                if(is_a($item, "App\Project")){
                    $item->previous_lesson_id = $value;
                    $item->save();
                } else {
                    $item->previous_lesson_id = $value;
                    $item->save();
                    $found_next_lesson = true;
                    break;
                }
            } else {
                if($item->id == $lesson->id){
                    $found_this_lesson = true;
                }
            }
        }
    }


    /**
     * Summary of Completedness
     * @param mixed $userID
     * @return object containing (Completed, ExerciseCount, PercComplete)
     */
    public function CompletionStats($userID)
    {
        $idParsed = intval($userID);
        $database = config("database.connections.mysql.database");
        $results = DB::select(DB::raw("SELECT lsn.module_id, COUNT(ep.id) as Completed, COUNT(e.id) as ExerciseCount, (COUNT(ep.id)/COUNT(e.id)) as PercComplete
                                        FROM $database.lessons lsn
                                        JOIN $database.exercises e ON e.lesson_id = lsn.id
                                        LEFT JOIN (SELECT id, exercise_id FROM $database.exercise_progress WHERE user_id = :userID AND completion_date IS NOT NULL)
                                        AS ep ON ep.exercise_id = e.id WHERE lsn.module_id = :moduleID
                                        GROUP BY lsn.module_id"), array('userID' => $idParsed, 'moduleID' =>$this->id));

        //TODO: need to add projects here for completion stats
        // A project is completed when its date to complete has passed.
        // Grades are not being stored in the system; so we do not need to worry about graded status.
        // It might be nice to worry about submitted status.

        //print_r($results);
        if(!is_null($results) and !empty($results)){
            return $results[0];
        }

        return null;
    }

    /**
     * Get the count of Exercises in this Module
     * @return int Count of Exercises in this module.
     */
    public function ExerciseCount()
    {
        $database = config("database.connections.mysql.database");
        $results = DB::select(DB::raw("SELECT COUNT(ex.id) AS ExerciseCount FROM $database.lessons lsn
                                        JOIN $database.exercises ex ON lsn.id = ex.lesson_id
                                        WHERE lsn.module_id = :moduleID
                                        GROUP BY lsn.module_id"), array('moduleID' =>$this->id));

        //print_r($results);

        if(!empty($results[0]->ExerciseCount)){
            return $results[0]->ExerciseCount;
        }

        return 0;
    }

    /**
     * Get the count of Lessons in this Module
     * @return int Count of Lessons in this module.
     */
    public function LessonCount()
    {
        $database = config("database.connections.mysql.database");
        $results = DB::select(DB::raw("SELECT COUNT(id) AS LessonCount FROM $database.lessons
                                        WHERE lsn.module_id = :moduleID
                                        GROUP BY lsn.module_id"), array('moduleID' =>$this->id));

        //print_r($results);
        return $results[0]->LessonCount;
    }

    public function deepCopy($concept_id, $previous_module_id)
    {
        $old_module = Module::getModule($this->id);

        // Copy the existing module
        $new_module = new Module();
        $new_module->name = $old_module->name;
        $new_module->open_date = $old_module->open_date;
        $new_module->concept_id = $concept_id;
        $new_module->previous_module_id = $previous_module_id;
        $new_module->owner_id = auth()->user()->id;
        $new_module->save();

        // Copy any components in the module to be copied
        if(count($old_module->components) > 0){
            $previous_lesson_id = null;
            foreach($old_module->components as $component){
                $new_component = $component->deepCopy($new_module->id, $previous_lesson_id);
                $previous_lesson_id = $new_component->id;
            }
        }

        return $new_module;
    }

    public static function getModule($module_id)
    {
        $module = Module::where('id', $module_id)->
        with(['unorderedLessons', 'unorderedProjects'])->first();

        $module->components = $module->componentsCollection();

        return $module;
    }

    /**
     * Returns the modules open date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function OpenDate()
    {
        return DateTime::createFromFormat(config("app.dateformat"), $this->open_date);
    }

    /**
     * Returns the modules open date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function getOpenDate($format = 'm/d/Y h:i a')
    {
        return date_format(DateTime::createFromFormat(config("app.dateformat"), $this->open_date), $format);
    }

    /**
     * returns true if the module has been completed
     * NM. that is not what this does. this is not good.
     * We should not mix return types.
     */
    public function completed()
    {
        $user_id = auth()->user()->id;

        $lessons = $this->lessons();

        $completed = true;

        foreach($lessons as $lesson){
            $les_completed = $lesson->completed();
            if($les_completed !== true){
                return $les_completed;
            }
        }

        return $completed;
    }

    /**
     *
     */
    public function completion()
    {
        $module_completion = array();

        foreach($this->lessons() as $lesson){
            $les_arr = array();
            foreach($lesson->exercises() as $exercise){
                $cur_ex_progress = ExerciseProgress::where('user_id', auth()->user()->id)->where('exercise_id', $exercise->id)->first();

                if(!empty($cur_ex_progress)){
                    $ex_arr = $cur_ex_progress->completed();
                } else {
                    $ex_arr = 0;
                }

                $les_arr[$exercise->id] = $ex_arr;
            }

            $module_completion[$lesson->id] = $les_arr;
        }

        return $module_completion;
    }

    public function componentsCollection()
    {
        $components = array();

        // Make sure there is at least 1 component 
        if($this->unorderedLessons->count() > 0 or $this->unorderedProjects->count() > 0){
            $last_lesson_id = null;

            // Find any projects with a null previous_lesson_id field and add them to array
            $projects = $this->unorderedProjects->where('previous_lesson_id', null);
            if(!is_null($projects) and $projects->count() > 0){
                foreach($projects as $project){
                    array_push($components, $project);
                }
            }

            // Check for a lesson with a null previous_lesson_id field and add it to the array 
            $lesson = $this->unorderedLessons->where('previous_lesson_id', null)->first();
            if(!is_null($lesson)){
                array_push($components, $lesson);

                // Set last_lesson_id to the found lessons id
                $last_lesson_id = $lesson->id;
            }

            // While last_lesson_id isn't null
            while($last_lesson_id != null){
                // Check for projects where previous_lesson_id = last_lesson_id and add them to array
                $projects = $this->unorderedProjects->where('previous_lesson_id', $last_lesson_id);
                if(!is_null($projects) and $projects->count() > 0){
                    foreach($projects as $project){
                        array_push($components, $project);
                    }
                }

                // Check for a lesson where previous_lesson_id = last_lesson_id and add it to the array
                $lesson = $this->unorderedLessons->where('previous_lesson_id', $last_lesson_id)->first();
                if(!is_null($lesson)){
                    array_push($components, $lesson);

                    // Set last_lesson_id to the found lessons id
                    $last_lesson_id = $lesson->id;
                } else {
                    $last_lesson_id = null;
                }
            }

            if(count($components) > 0){
                return collect($components);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
