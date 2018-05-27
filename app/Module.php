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
    
    public function unorderedLessons()
    {
        return $this->hasMany('App\Lesson');
    }

    public function unorderedProjects()
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

    public function lessons()
    {
        $ordered_lessons = array();

        if(count($this->unorderedLessons) > 0){
            $lesson = $this->unorderedLessons()->whereNull('previous_lesson_id')->get()[0];

            array_push($ordered_lessons, $lesson);

            $done = false;
            while(!$done){
                $lesson = self::nextLesson($lesson->id);

                if(!is_null($lesson)){
                    array_push($ordered_lessons, $lesson);
                } else {
                    $done = true;
                }
            }
        }

        return $ordered_lessons;
    }

    public function lessonsAndProjects()
    {
        $ordered_contents = array();

        // Add any projects that don't come after a lesson
        $null_projects = $this->unorderedProjects()->whereNull('previous_lesson_id')->orderBy('open_date', 'ASC')->get();
        if(count($null_projects) > 0){
            foreach($null_projects as $null_project){
                array_push($ordered_contents, $null_project);
            }
        }

        if(count($this->unorderedLessons) > 0){
            $lessons = $this->lessons();

            // Add all lessons in order of dependance
            foreach($lessons as $lesson){
                array_push($ordered_contents, $lesson);

                // Add the projects that come after each lesson
                $projects = $this->unorderedProjects()->where('previous_lesson_id', $lesson->id)->orderBy('open_date', 'ASC')->get();
                foreach($projects as $project){
                    array_push($ordered_contents, $project);
                }
            }
        }

        return $ordered_contents;
    }

    public function nextLesson($id)
    {
        $next_lesson = $this->unorderedLessons()->where('previous_lesson_id', $id)->get();

        if(count($next_lesson) > 0){
            return $next_lesson[0];
        } else {
            return null;
        }
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
