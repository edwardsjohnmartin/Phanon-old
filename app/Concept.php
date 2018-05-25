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

    public function unorderedModules()
    {
        return $this->hasMany('App\Module');
    }

    public function user()
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
    public function removeModule($id)
    {
        $modules = $this->modules();

        // If the module to be removed is the only module in the concept, ordering won't need to be fixed
        if(count($modules) > 1){
            // Case 1: module was the first module in the concept
                // Find the next module and change its previous_module_id to be null   
            if($id == $modules[0]->id){
                $next_module = $this->nextModule($id);
                $next_module->previous_module_id = null;
                $next_module->save();

                return;
            } 

            // Case 2: module was not the first or last module in the concept
                // Change the next module's previous_module_id to be the module that came before the module to be removed
            if($id != $modules[0]->id and $id != end($modules)->id){
                $module = Module::find($id);
                $next_module = $this->nextModule($id);
                $next_module->previous_module_id = $module->previous_module_id;
                $next_module->save();
            
                return;
            }
        }
    }
}
