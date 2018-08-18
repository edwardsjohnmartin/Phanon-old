<?php
namespace App;

use App\ObjectTools;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $name Identifying name of the Concept.
 * @property int $course_id The id of the Course this Concept is part of.
 * @property int $previous_concept_id The id of the Concept within the same Course that needs to be done before this Concept can be attempted.
 * @property int $owner_id The id of the user that created this Concept.
 * @property int $updated_by The id of the user to last update this Concept.
 * @property \datetime $created_at The date the Concept was created.
 * @property \datetime $updated_at The date the Concept was last updated.
 */
class Concept extends Model
{
    use SoftDeletes;

    // Table Name
    public $table = 'concepts';

    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    // tempModules
    public $tempModules = [];

    // Toggle whether to use Eager loading
    public $eagerLoading = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Relationship function
     * Returns the course this concept belongs to
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    /**
     * Relationship function
     * Returns an array of modules contained in this concept
     */
    public function unorderedModules()
    {
        return $this->hasMany('App\Module');
    }

    /**
     * Relationship function
     * Returns the user this concept belongs to
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Returns an array of the modules within the concept in their correct order
     */
    public function modules()
    {
        // Get the modules that are in the concept
        $modules = $this->unorderedModules;

        // Create the array that will store the modules in the correct order
        $ordered_modules = array();

        if($this->eagerLoading){
            // this approach should use the eager loaded and not do additional DB calls.
            // saved ~200 ms in the test cases.
            foreach($modules as $module){
                $prevId = $module->previous_module_id;
                $index = ObjectTools::getIndex($ordered_modules, $prevId);
                if($index > -1){
                    // add after previous
                    array_splice($ordered_modules,$index+1,0,[$module]);
                }else{
                    // add at end
                    $ordered_modules[] = $module;
                }

            }
        }else{
            // uses lazy loading
            if(count($modules) > 0){
                // Get the first module (its previous_module_id is null) and put it into the ordered_modules array
                $module = $this->unorderedModules()->whereNull('previous_module_id')->get()[0];
                array_push($ordered_modules, $module);

                $done = false;
                while($done == false){
                    $next_module = self::nextModule($module->id);

                    if(!is_null($next_module)){
                        $module = $next_module;

                        array_push($ordered_modules, $module);
                    } else {
                        $done = true;
                    }
                }
            }
        }
        return $ordered_modules;
    }

    /**
     * Returns the module that comes after the given module within the concept
     */
    public function nextModule($id)
    {
        $next_module = $this->unorderedModules()->where('previous_module_id', $id)->get();
        if(count($next_module) > 0){
            return $next_module[0];
        } else {
            return null;
        }
    }

    /**
     * Remove a module from the concept and fix any inconsistencies in the ordering it may cause
     */
    public function removeModule($module)
    {
        $next_module = $this->nextModule($module->id);

        if(!is_null($next_module)){
            $next_module->previous_module_id = $module->previous_module_id;
            $next_module->save();
        }
    }

    /**
     * Summary of Completedness
     * @param mixed $userID
     * @return object containing (Completed, ExerciseCount, PercComplete)
     */
    public function CompletionStats($userID){
        $idParsed = intval($userID);
        $database = config("database.connections.mysql.database");
        // Changed this to check if the completion_date is not null instead of the last_correct_contents
        // This is because if the exercise is auto completed, the completion_date would be filled and not the last_correct_contents
        $results = DB::select(DB::raw("SELECT m.concept_id, COUNT(ep.id) as Completed, COUNT(e.id) as ExerciseCount, (COUNT(ep.id)/COUNT(e.id)) as PercComplete
                                        FROM $database.modules m  JOIN $database.lessons lsn ON m.id = lsn.module_id
                                        JOIN $database.exercises e ON e.lesson_id = lsn.id
                                        LEFT JOIN (SELECT id, exercise_id FROM $database.exercise_progress
	                                        WHERE user_id = :userID
                                            AND completion_date IS NOT NULL) AS ep ON ep.exercise_id = e.id
                                        WHERE m.concept_id = :conceptID
                                        GROUP BY m.concept_id"), array('userID' => $idParsed, 'conceptID' =>$this->id));

        //print_r($results);
        return $results[0];
    }

    /**
     *
     */
    public function completed()
    {
        $user_id = auth()->user()->id;

        $modules = $this->modules();

        $completed = true;

        foreach($modules as $module){
            $mod_completed = $module->completed();
            if($mod_completed !== true){
                return $mod_completed;
            }
        }

        return $completed;
    }

    public function modulesCollection()
    {
        $modules = array();

        $module = $this->unorderedModules->where('previous_module_id', null)->first();

        if(is_null($module)){
            return $this->unorderedModules;
        }

        $module->components = $module->componentsCollection();
        unset($module->unorderedLessons);
        unset($module->unorderedProjects);
        array_push($modules, $module);

        $done = false;

        while(!$done){
            $next_module = $this->unorderedModules->where('previous_module_id', $module->id)->first();

            if(!is_null($next_module)){
                $module = $next_module;
                $module->components = $module->componentsCollection();
                unset($module->unorderedLessons);
                unset($module->unorderedProjects);
                array_push($modules, $module);
            } else {
                $done = true;
            }
        }

        if(count($modules) > 0){
            return collect($modules);
        } else {
            return null;
        }
    }

    public function deepCopy($course_id, $previous_concept_id)
    {
        $old_concept = Concept::getConcept($this->id);

        // Copy the existing concept
        $new_concept = new Concept();
        $new_concept->name = $old_concept->name;
        $new_concept->course_id = $course_id;
        $new_concept->previous_concept_id = $previous_concept_id;
        $new_concept->owner_id = auth()->user()->id;
        $new_concept->save();

        // Copy any modules in the concept to be cloned
        if(count($old_concept->modules) > 0){
            $previous_module_id = null;
            foreach($old_concept->modules as $module){
                $new_module = $module->deepCopy($new_concept->id, $previous_module_id);
                $previous_module_id = $new_module->id;
            }
        }

        return $new_concept;
    }

    public static function getConcept($concept_id)
    {
        $concept = Concept::where('id', $concept_id)->
        with(['unorderedModules' => function($modules){
            $modules->with(['unorderedLessons', 'unorderedProjects']);
        }])->first();

        $concept->modules = $concept->modulesCollection();
        unset($concept->unorderedModules);

        return $concept;
    }
}
