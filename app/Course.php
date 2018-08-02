<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ObjectTools;
use Spatie\Permission\Models\Role;
use App\Enums\Roles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use DateTime;

 /** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $name Identifying name of the Course.
 * @property \datetime $open_date The date this Course becomes available.
 * @property \datetime $close_date The date this Course is no longer available.
 * @property int $owner_id The id of the user that created this Course.
 * @property int $updated_by The id of the user to last update this Course.
 * @property \datetime $created_at The date the Course was created.
 * @property \datetime $updated_at The date the Course was last updated.
 */

class Course extends Model
{
    // Table Name
    public $table = 'courses';

    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

// Toggle whether to use Eager loading
    public $eagerLoading = false;

    /**
     * Relationship function
     * Returns an array of concepts contained in this course
     */
    public function unorderedConcepts()
    {
        return $concepts = $this->hasMany('App\Concept');
    }

    /**
     * Relationship function
     * Returns the user this course belongs to
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    /**
     * Relationship function
     * Returns an array of users in this course
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role_id');
    }

    /**
     * Returns an array of all users in the course with the specified role
     */
    public function getUsersByRole($role_id)
    {
        // Validate the role being requested exists
        if(Role::where('id', '=', $role_id)->exists()){
            return $this->users()->wherePivot('role_id', $role_id)->get();
        }
    }


    /**
     * Returns an array of the concepts within the course in their correct order
     */
    public function concepts()
    {
        // Get the concepts that are in this course
        $concepts = $this->unorderedConcepts;

        // Create the array that will store the concepts in the correct order
        $ordered_concepts = array();

        if($this->eagerLoading){
            // this approach should use the eager loaded and not do additional DB calls.
            // saved ~100 ms in the test cases.
            foreach($concepts as $concept){
                $prevId = $concept->previous_concept_id;
                $index = ObjectTools::getIndex($ordered_concepts, $prevId);
                if($index > -1){
                    // add after previous
                    array_splice($ordered_concepts,$index+1,0,[$concept]);
                }else{
                    // add at end
                    $ordered_concepts[] = $concept;
                }

            }
        }else{
            // uses lazy loading
            if(count($concepts) > 0){
                // Get the first concept (its previous_concept_id is null) and put it into the array
                $concept = $this->unorderedConcepts()->whereNull('previous_concept_id')->get()[0];
                array_push($ordered_concepts, $concept);

                $done = false;
                while($done == false){
                    $next_concept = self::nextConcept($concept->id);

                    if(!is_null($next_concept)){
                        $concept = $next_concept;

                        array_push($ordered_concepts, $concept);
                    } else {
                        $done = true;
                    }
                }
            }
        }
        return $ordered_concepts;
    }


    /**
     * Returns an array of all projects within the course.
     */
    public function projects()
    {
        $projects = [];

        foreach($this->concepts() as $concept){
            foreach($concept->modules() as $module){
                foreach($module->projects() as $project){
                    array_push($projects, $project);
                }
            }
        }

        return $projects;
    }

    public function teams()
    {
        return $this->hasMany('App\Team');
    }

    /**
     * Returns the concept that comes after the given concept within the course
     */
    public function nextConcept($id)
    {
        $next_concept = $this->unorderedConcepts()->where('previous_concept_id', $id)->get();
        if(count($next_concept) > 0){
            return $next_concept[0];
        } else {
            return null;
        }
    }

    /**
     * Remove the concept from this course and retains the ordered of the remaining concepts
     */
    public function removeConcept($concept)
    {
        $next_concept = $this->nextConcept($concept->id);

        if(!is_null($next_concept)){
            $next_concept->previous_concept_id = $concept->previous_concept_id;
            $next_concept->save();
        }
    }

    /**
     * Adds the specified user to the course as the specified role
     */
    public function addUserAsRole($user_id, $role_id)
    {
        if(Role::where('id', '=', $role_id)->exists() and User::where('id', '=', $user_id)->exists()){
            $arr = array($user_id => array('course_id' => $this->id, 'role_id' => $role_id));
            $this->users()->syncWithoutDetaching($arr);
        }
    }

    /**
     * Function that adds users to the course with the specified role_id
     * Takes in an array of user_ids and a single role_id
     */
    public function addUsersAsRole($user_ids, $role_id)
    {
        // Make sure the role with passed-in id exists and that the array of user ids isnt empty
        if(Role::where('id', '=', $role_id)->exists() and !empty($user_ids)){
            // Create array to hold each user to add to the course
            $users = array();

            // Add each user to $users along with the role they will have in the course
            foreach($user_ids as $user_id){
                $users = array_add($users, $user_id, ['role_id' => $role_id]);
            }

            // Save the users to the course in the database
            $this->users()->syncWithoutDetaching($users);
        }
    }

    /**
     *
     */
    public function deepCopy()
    {
        $new_course = new Course();
        $new_course->name = $this->name;
        $new_course->open_date = $this->open_date;
        $new_course->close_date = $this->close_date;

        return $new_course;
    }

    /**
     * Returns the courses open date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function getOpenDate($format = 'm/d/Y h:i a')
    {
        return date_format(DateTime::createFromFormat(config("app.dateformat"),
            $this->open_date), $format);
    }

    /**
     * Returns the courses close date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function getCloseDate($format = 'm/d/Y h:i a')
    {
        return date_format(DateTime::createFromFormat(config("app.dateformat"),
            $this->close_date), $format);
    }

    /**
     * Takes in a user id and returns their role within the course.
     */
    public function getUsersRole($user_id)
    {
        if($this->owner_id == $user_id){
            // Get admin role if user owns course
            $role = Role::where('name', Roles::ADMIN)->first();
        } else {
            // Get users role within the course
            $selection = DB::table('course_user')->select('role_id')->where('course_id', $this->id)->where('user_id', $user_id)->first();
            if(!is_null($selection)){
                $role_id = $selection->role_id;
                $role = Role::find($role_id);
            } else {
                $role = null;
            }
        }

        return $role;
    }

    /**
     * Takes in a user id and returns a boolean of whether the user is enrolled in the course as any role or if they own(created) the course.
     */
    public function isUserEnrolledOrOwner($user_id)
    {
        if($this->owner_id == $user_id){
            return true;
        }

        foreach($this->users as $user){
            if($user->id == $user_id){
                return true;
            }
        }

        return false;
    }

    public static function getCourse($course_id)
    {
        $course = Course::where('id', $course_id)->
        with(['unorderedConcepts' => function($concepts){
            $concepts->with(['unorderedModules' => function($modules){
                $modules->with(['unorderedLessons', 'unorderedProjects']);
            }]);
        }])->first();

        
        $course->concepts = $course->conceptsCollection();
        unset($course->unorderedConcepts);

        return $course;
    }

    public function conceptsCollection()
    {
        $concepts = array();

        $concept = $this->unorderedConcepts->where('previous_concept_id', null)->first();

        if(is_null($concept)){
            return "boo";
        } else {
            $concept->modules = $concept->modulesCollection();
            unset($concept->unorderedModules);
            array_push($concepts, $concept);

            $done = false;

            while(!$done){
                $next_concept = $this->unorderedConcepts->where('previous_concept_id', $concept->id)->first();

                if(!is_null($next_concept)){
                    $concept = $next_concept;
                    $concept->modules = $concept->modulesCollection();
                    unset($concept->unorderedModules);
                    array_push($concepts, $concept);
                } else {
                    $done = true;
                }
            }

            if(count($concepts) > 0){
                return collect($concepts);
            } else {
                return null;
            }
        }
    }

    /**
     * Summary of ExerciseProgress
     * @param mixed $userID
     * @return object containing (Completed, ExerciseCount, PercComplete)
     */
    public function ExerciseProgress($userID){
        $idParsed = intval($userID);
        //$progress = DB::table('exercise_progress')->
        //    join('exercises', 'exercise_progress.exercise_id','=','exercises.id')->
        //    join('lessons', 'exercises.lesson_id','=','lessons.id')->
        //    join('modules', 'lessons.module_id','=','modules.id')->
        //    join('concepts', 'modules.concept_id','=','concepts.id')->
        //    select('exercise_progress.*')->
        //    where([
        //        ['concepts.course_id', $this->id],
        //        ['exercise_progress.user_id',$idParsed]
        //    ])->get();
        $progress = ExerciseProgress::
            join('exercises', 'exercise_progress.exercise_id','=','exercises.id')->
            join('lessons', 'exercises.lesson_id','=','lessons.id')->
            join('modules', 'lessons.module_id','=','modules.id')->
            join('concepts', 'modules.concept_id','=','concepts.id')->
            select('exercise_progress.*')->
            where([
                ['concepts.course_id', $this->id],
                ['exercise_progress.user_id',$idParsed]
            ])->get();
        return $progress;
    }

}
