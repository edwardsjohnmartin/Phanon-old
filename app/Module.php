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
        return $this->hasMany('App\Lesson');
    }

    public function projects(){
        return $this->hasMany('App\Project');
    }

    public function course(){
        return $this->belongsTo('App\Course');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
