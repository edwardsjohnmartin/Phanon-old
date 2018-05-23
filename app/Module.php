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
    
    public function lessons()
    {
        return $this->hasMany('App\Lesson');
    }

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    public function concept()
    {
        return $this->belongsTo('App\Concept');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deepCopy()
    {
        $new_module = new Module();
        $new_module->name = $this->name;
        $new_module->open_date = $this->open_date;
        $new_module->close_date = $this->close_date;

        return $new_module;    
    }
}
