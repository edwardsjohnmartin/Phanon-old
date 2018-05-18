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

    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deep_copy()
    {
        $new_exercise = new Exercise();
        $new_exercise->prompt = $this->prompt;
        $new_exercise->pre_code = $this->pre_code;
        $new_exercise->start_code = $this->start_code;
        $new_exercise->test_code = $this->test_code;

        return $new_exercise;
    }
}
