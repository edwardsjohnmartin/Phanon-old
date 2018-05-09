<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Module;

class Course extends Model
{
    // Table Name
    public $table = 'courses';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function modules(){
        return $this->hasMany('App\Module')->withTimestamps();
    }
}
