<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    // Table Name
    public $table = 'lessons';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    // tempExercises
    public $tempExercises = [];
    

    /**
     * Relationship function
     * Returns the module this lesson belongs to
     */
    public function module()
    {
        return $this->belongsTo('App\Module');
    }

    /**
     * Relationship function
     * Returns an array of exercises contained in this lesson
     */
    public function unorderedExercises()
    {
        return $this->hasMany('App\Exercise');
    }

    /**
     * Relationship function
     * Returns the user this lesson belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Returns an array of the exercises within the lesson in their correct order
     */
    public function exercises()
    {
        // Get the exercises that are in the lesson
        $exercises = $this->unorderedExercises;

        // Create the array that will store the exercises in the correct order
        $ordered_exercises = array();

        if(count($exercises) > 0){
            // Get the first exercise (its previous_exercise_id is null) and put it into the array
            $exercise = $this->unorderedExercises()->whereNull('previous_exercise_id')->get()[0];
            array_push($ordered_exercises, $exercise);

            $done = false;
            while($done == false){
                $next_exercise = self::nextExercise($exercise->id);

                if(!is_null($next_exercise)){
                    $exercise = $next_exercise;

                    array_push($ordered_exercises, $exercise);
                } else {
                    $done = true;
                } 
            }
        }
        
        return $ordered_exercises;
    }

    /**
     * Returns the exercise that comes after the given exercise within the lesson
     */
    public function nextExercise($id)
    {
        $next_exercise = $this->unorderedExercises()->where('previous_exercise_id', $id)->get();
        
        if(count($next_exercise) > 0){
            return $next_exercise[0];
        } else {
            return null;
        }
    }

    /** 
     * Removes the exercise from this lesson and retains the ordering of the remaining exercises
     */
    public function removeExercise($exercise)
    {
        $next_exercise = $this->nextExercise($exercise->id);

        if(!is_null($next_exercise)){
            $next_exercise->previous_exercise_id = $exercise->previous_exercise_id;
            $next_exercise->save();
        }
    }

    public function deepCopy()
    {
        $new_lesson = new Lesson();
        $new_lesson->name = $this->name;

        return $new_lesson;
    }
}
