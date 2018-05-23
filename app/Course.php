<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // Table Name
    public $table = 'courses';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function modules()
    {
        return $this->hasMany('App\Module');
    }

    public function deepCopy()
    {
        $new_course = new Course();
        $new_course->name = $this->name;
        $new_course->open_date = $this->open_date;
        $new_course->close_date = $this->close_date;

        return $new_course;
    }
}
