<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Module extends Model
{
    // Table Name
    public $table = 'modules';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;
    
    /**
     * Relationship function
     * Returns the concept this module belongs to
     */
    public function concept()
    {
        return $this->belongsTo('App\Concept');
    }

    /**
     * Relationship function
     * Returns an array of lessons contained in this module
     */
    public function unorderedLessons()
    {
        return $this->hasMany('App\Lesson');
    }

    /**
     * Relationship function
     * Returns an array of projects contained in this module
     */
    public function unorderedProjects()
    {
        return $this->hasMany('App\Project');
    }

    /**
     * Relationship function
     * Returns the user this module belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Return an array of only the lessons in the module in the order they should appear in
     */
    public function lessons()
    {
        // Get the lessons that are in the module
        $lessons = $this->unorderedLessons;

        // Create the array that will store the lessons in the correct order
        $ordered_lessons = array();

        if(count($lessons) > 0){
            // Get the first lesson (its previous_lesson_id is null) and put it into the array
            $lesson = $this->unorderedLessons()->whereNull('previous_lesson_id')->get()[0];
            array_push($ordered_lessons, $lesson);

            $done = false;
            while(!$done){
                $next_lesson = self::nextLesson($lesson->id);

                if(!is_null($next_lesson)){
                    $lesson = $next_lesson;

                    array_push($ordered_lessons, $lesson);
                } else {
                    $done = true;
                }
            }
        }

        return $ordered_lessons;
    }

    /**
     * Return an array of projects in the module, ordered by open_date
     */
    public function projects()
    {
        return $this->unorderedProjects()->orderBy('open_date')->get();
    }

    /**
     * Returns an array of all lessons and projects contained in the module in their correct order
     */
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

    /**
     * Returns the lesson that comes after the given lesson within the module
     */
    public function nextLesson($id)
    {
        $next_lesson = $this->unorderedLessons()->where('previous_lesson_id', $id)->get();

        if(count($next_lesson) > 0){
            return $next_lesson[0];
        } else {
            return null;
        }
    }

    /**
     * Removes the lesson from this modules and retains the ordering of the remaining lessons and projects
     */
    public function removeLesson($lesson)
    {
        $lessons = $this->lessons();

        // This will be the value to change the previous_lesson_id any items that come after the lesson to be deleted
        $value = $lesson->previous_lesson_id;

        // Boolean value to keep track of whether the lesson to be deleted has been found in the module
        // and if a lesson after that (ie. the stopping point) has been found
        $found_this_lesson = false;
        $found_next_lesson = false;

        foreach($this->lessonsAndProjects() as $item){
            if($found_this_lesson and !$found_next_lesson){
                if(is_a($item, "App\Project")){
                    $item->previous_lesson_id = $value;
                    $item->save();
                } else {
                    $item->previous_lesson_id = $value;
                    $item->save();
                    $found_next_lesson = true;
                    break;
                }
            } else {
                if($item->id == $lesson->id){
                    $found_this_lesson = true;
                }
            }   
        }    
    }

    public function deepCopy()
    {
        $new_module = new Module();
        $new_module->name = $this->name;
        $new_module->open_date = $this->open_date;

        return $new_module;    
    }

    /**
     * Returns the modules open date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function getOpenDate($format = 'm/d/Y h:i a')
    {
        return date_format(DateTime::createFromFormat('Y-m-d G:i:s', $this->open_date), $format);
    }
}
