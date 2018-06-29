<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $prompt Instructions given to the user for this Exercise.
 * @property string $pre_code Code that will be ran in the compiler directly before the user's submitted code.
 *                             The user will be able to use this code in their submission if they desire.
 * @property string $start_code Initial code given to the user to start the Exercise with.
 * @property string $test_code Code that is run immediately following the user's submitted code. 
 *                             It is used to validate the user's answer against to check for correctness.
 * @property string $solution Code defined by the creator of this Exercise that solves it.
 * @property int $lesson_id The id of the Lesson this Exercise is part of.
 * @property int $previous_exercise_id The id of the Exercise within the same Lesson that needs to be done before this Exercise can be attempted.
 * @property int $owner_id The id of the user that created this Exercise.
 * @property int $updated_by The id of the user to last update this Exercise.
 * @property \datetime $created_at The date the Exercise was created.
 * @property \datetime $updated_at The date the Exercise was last updated.
 */
class Exercise extends Model
{
    // Table Name
    public $table = 'exercises';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    /**
     * Relationship function
     * Returns the lesson this exercise belongs to
     */
    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

    /**
     * Returns the user this exercise belongs to
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function deepCopy()
    {
        $new_exercise = new Exercise();
        $new_exercise->prompt = $this->prompt;
        $new_exercise->pre_code = $this->pre_code;
        $new_exercise->start_code = $this->start_code;
        $new_exercise->test_code = $this->test_code;

        return $new_exercise;
    }

    /**
     * 
     */
    public function getProgressForUser($user_id = null)
    {
        if(!isset($user_id)){
            $user_id = auth()->user()->id;
        }

        $exercise_progress = ExerciseProgress::where('exercise_id', $this->id)->where('user_id', $user_id)->first();

        if(empty($exercise_progress) or is_null($exercise_progress)){
            $exercise_progress = new ExerciseProgress();
            $exercise_progress->user_id = $user_id;
            $exercise_progress->exercise_id = $this->id;
            $exercise_progress->save();
        }

        return $exercise_progress;
    }
}
