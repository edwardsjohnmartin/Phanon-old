<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Table Name
    public $table = 'projects';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    /**
     * Relationship function
     * Returns the module this project belongs to
     */
    public function module()
    {
        return $this->belongsTo('App\Module');
    }

    /**
     * Relationship function
     * Returns the user this project belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deepCopy()
    {
        $new_project = new Project();
        $new_project->name = $this->name;
        $new_project->open_date = $this->open_date;
        $new_project->close_date = $this->close_date;
        $new_project->prompt = $this->prompt;
        $new_project->pre_code = $this->pre_code;
        $new_project->start_code = $this->start_code;

        return $new_project;
    }
}