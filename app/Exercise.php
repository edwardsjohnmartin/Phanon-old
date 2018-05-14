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

    public function lessons(){
        return $this->belongsToMany('App\Lesson');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
