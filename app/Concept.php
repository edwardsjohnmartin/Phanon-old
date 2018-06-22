<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
 /** Property Identification for Intellisense help.
 * @property int $id Unique Database Identifier
 * @property string $name Identifying name of the Concept.
 * @property int $course_id The id of the Course this Concept is part of.
 * @property int $previous_concept_id The id of the Concept within the same Course that needs to be done before this Concept can be attempted.
 * @property int $owner_id The id of the user that created this Concept.
 * @property int $updated_by The id of the user to last update this Concept.
 * @property \datetime $created_at The date the Concept was created.
 * @property \datetime $updated_at The date the Concept was last updated.
 */
class Concept extends Model
{
    // Table Name
    public $table = 'concepts';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    // tempModules
    public $tempModules = [];

    /**
     * Relationship function
     * Returns the course this concept belongs to
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    /**
     * Relationship function
     * Returns an array of modules contained in this concept
     */
    public function unorderedModules()
    {
        return $this->hasMany('App\Module');
    }

    /**
     * Relationship function
     * Returns the user this concept belongs to
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Returns an array of the modules within the concept in their correct order
     */
    public function modules()
    {
        // Get the modules that are in the concept
        $modules = $this->unorderedModules;

        // Create the array that will store the modules in the correct order
        $ordered_modules = array();

        if(count($modules) > 0){
            // Get the first module (its previous_module_id is null) and put it into the ordered_modules array
            $module = $this->unorderedModules()->whereNull('previous_module_id')->get()[0];
            array_push($ordered_modules, $module);

            $done = false;
            while($done == false){
                $next_module = self::nextModule($module->id);

                if(!is_null($next_module)){
                    $module = $next_module;

                    array_push($ordered_modules, $module);
                } else {
                    $done = true;
                } 
            }
        }
        
        return $ordered_modules;
    }

    /**
     * Returns the module that comes after the given module within the concept
     */
    public function nextModule($id)
    {
        $next_module = $this->unorderedModules()->where('previous_module_id', $id)->get();
        if(count($next_module) > 0){
            return $next_module[0];
        } else {
            return null;
        }
    }

    /**
     * Remove a module from the concept and fix any inconsistencies in the ordering it may cause
     */
    public function removeModule($module)
    {
        $next_module = $this->nextModule($module->id);

        if(!is_null($next_module)){
            $next_module->previous_module_id = $module->previous_module_id;
            $next_module->save();
        }
    }
}
