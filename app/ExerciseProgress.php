<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property int $user_id The id of the user this is tracking progress for.
 * @property int $exercise_id The id of the Exercise this is progress for.
 * @property string $last_contents The contents of the user's answer the last time this Exercise was ran.
 * @property \datetime $last_run_date The date of the last time the user ran this Exercise.
 * @property string $last_correct_contents The contents of the user's answer the last time this Exercise was ran and was correct.
 * @property \datetime $last_correct_run_date The date of the last time the user ran this Exercise and their answer was correct.
 * @property \datetime $completion_date The date of the first time the user ran this Exercise correctly.
 */
class ExerciseProgress extends Model
{
    // Table Name
    public $table = 'exercise_progress';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = false;

    // Returns whether or not the exercise was completed correctly
    public function completed()
    {
        return isset($this->completion_date);
    }

    /**
     *  Takes in whether an exercise was completed correctly and saves its contents and current date to the database
     */
    public function saveProgress($contents, $success)
    {
        $now = Carbon\Carbon::now();

        if($success){
            $this->last_correct_contents = $contents;
            $this->last_correct_run_date = $now;

            if(!$this->completed()){
                $this->completion_date = $now;
            }
        }else{
            $this->last_contents = $contents;
            $this->last_run_date = $now;
        }

        $this->save();
    }
}
