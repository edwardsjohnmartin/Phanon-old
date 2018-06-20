<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $prompt Instuctions given to user for this Project
 * @property string $pre_code Code that will be ran in the compiler directly before the user's submitted code.
 * @property string $start_code Code that will be provided to the user to modify for their solution.
 * @property string $test_code Code that is run immediately following the users 
 *                              submitted code containing functions to test and validate the user's submission
 * @property int $lesson_id Lesson ID of the module this Exercise is part of.
 * @property int $previous_exercise_id Previous Exercise that needs to be done before this Exercise can be attempted.
 * @property int $user_id user that ...
 * @property \datetime $created_at when this object was added to the database
 * @property \datetime $updated_at when this object was last changed in the database
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
    public function user()
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
}
