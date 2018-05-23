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

    public function module()
    {
        return $this->belongsTo('App\Module');
    }

    public function exercises()
    {
        return $this->hasMany('App\Exercise');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deepCopy()
    {
        $new_lesson = new Lesson();
        $new_lesson->name = $this->name;
        $new_lesson->open_date = $this->open_date;

        return $new_lesson;
    }

    /**
     * Returns an array of the exercises within the lesson in their correct order
     */
    public function orderedExercises()
    {
        $ordered_exercises = array();

        $exercise = $this->exercises()->whereNull('previous_exercise_id')->get()[0];

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
        
        return $ordered_exercises;
    }

    /**
     * Returns the exercise that comes after the given exercise within the lesson
     */
    public function nextExercise($id)
    {
        $next_exercise = $this->exercises()->where('previous_exercise_id', $id)->get();
        if(count($next_exercise) > 0){
            return $next_exercise[0];
        } else {
            return null;
        }
    }
}
