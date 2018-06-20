<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Enums\Roles;
use Illuminate\Support\Facades\DB;
use DateTime;


class Course extends Model
{
    // Table Name
    public $table = 'courses';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

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
    public function user()
    {
        return $this->belongsTo('App\User');
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
     * Relationship function
     * Returns an array of student users in the course
     */
    public function students()
    {
        return $this->belongsToMany(User::class)->wherePivot('role_id', DB::table('roles')->where('name', Roles::STUDENT)->first()->id);
    }

    /**
     * Relationship function
     * Returns an array of teacher assistants users in the course
     */
    public function assistants()
    {
        return $this->belongsToMany(User::class)->wherePivot('role_id', DB::table('roles')->where('name', Roles::TEACHING_ASSISTANT)->first()->id);
    }

    /**
     * Relationship function
     * Returns an array of teacher users in the course
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class)->wherePivot('role_id', DB::table('roles')->where('name', Roles::TEACHER)->first()->id);
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
        
        return $ordered_concepts;
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
}
