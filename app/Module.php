<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    // Table Name
    public $table = 'modules';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;
    
    public function lessons(){
        return $this->belongsToMany('App\Lesson')->withTimestamps();
    }

    public function courses(){
        return $this->belongsToMany('App\Course')->withTimestamps();
    }
}
