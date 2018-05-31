<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

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
