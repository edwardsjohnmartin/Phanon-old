<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

/** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $name identifying name of the Project
 * @property \datetime $open_date when this Project becomes available to the users
 * @property \datetime $close_date when this Project is no longer available to a user.
 * @property string $prompt Instuctions given to user for this Project
 * @property string $pre_code Code that will be ran in the compiler directly before the user's submitted code.
 * @property string $start_code Code that will be provided to the user to modify for their solution.
 * @property string $solution Code that is the solution to the project.
 * @property int $module_id Module ID of the module this project is part of.
 * @property int $previous_lesson_id Previous Lesson that needs to be done before this project can be attempted.
 * @property int $user_id user that ...
 * @property \datetime $created_at when this object was added to the database
 * @property \datetime $updated_at when this object was last changed in the database
 */
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

    /**
     * Returns the projects open date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function getOpenDate($format = 'm/d/Y h:i a')
    {
        return date_format(DateTime::createFromFormat(config("app.dateformat"), $this->open_date), $format);
    }

    /**
     * Returns the projects close date formatted with the passed-in format string.
     * If no format string is provided, it will use the default format.
     */
    public function getCloseDate($format = 'm/d/Y h:i a')
    {
        return date_format(DateTime::createFromFormat(config("app.dateformat"), $this->close_date), $format);
    }
}
