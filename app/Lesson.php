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

    public function module(){
        return $this->belongsTo('App\Module');
    }

    public function exercises(){
        return $this->belongsToMany('App\Exercise')->withTimestamps();
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
