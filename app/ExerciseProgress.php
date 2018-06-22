<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function completed()
    {
        return isset($this->completion_date);
    }
}
