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

    public function deep_copy()
    {
        $new_lesson = new Lesson();
        $new_lesson->name = $this->name;
        $new_lesson->open_date = $this->open_date;

        return $new_lesson;
    }
}
