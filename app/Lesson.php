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

    public function modules(){
        return $this->belongsToMany('App\Module')->withTimestamps();
    }
}
