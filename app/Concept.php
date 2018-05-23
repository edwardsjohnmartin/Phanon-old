<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    // Table Name
    public $table = 'concepts';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function modules()
    {
        return $this->hasMany('App\Module');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
