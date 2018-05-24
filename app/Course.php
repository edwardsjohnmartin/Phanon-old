<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // Table Name
    public $table = 'courses';
    
    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function unorderedConcepts()
    {
        return $concepts = $this->hasMany('App\Concept');
    }

    /**
     * Remove a concept from the course and fix any inconsistencies in the ordering it may cause
     */
    public function removeConcept($id)
    {
        // Case 1: concept was the first concept in the course and other concepts are in the course
            // Find the next concept and change its previous_concept_id to be null
        if(count($this->concepts()) > 1 and $id == $this->concepts()[0]->id){
            $next_concept = $this->nextConcept($id);
            $next_concept->previous_concept_id = null;
            $next_concept->save();

            return;
        } 

        // Case 2: concept was not the first or last concept in the course
            // Change the next concept's previous_concept_id to be the concept that came before the concept to be removed
        if(count($this->concepts()) > 1){
            if($id != $this->concepts()[0]->id and $id != end($this->concepts())->id){
                $concept = Concept::find($id);
                $next_concept = $this->nextConcept($id);
                $next_concept->previous_concept_id = $concept->previous_concept_id;
                $next_concept->save();

                return;
            }
        }
    }

    /**
     * Returns an array of the concepts within the course in their correct order
     */
    public function concepts()
    {
        $ordered_concepts = array();

        if(count($this->unorderedConcepts) > 0){
            $concept = $this->unorderedConcepts()->whereNull('previous_concept_id')->get()[0];

            array_push($ordered_concepts, $concept);

            $done = false;
            while($done == false){
                $next_concept = self::nextConcept($concept->id);

                if(!is_null($next_concept)){
                    $concept = $next_concept;

                    array_push($ordered_concepts, $concept);
                } else {
                    $done = true;
                } 
            }
        }
        
        return $ordered_concepts;
    }

    /**
     * Returns the concept that comes after the given concept within the course
     */
    public function nextConcept($id)
    {
        $next_concept = $this->unorderedConcepts()->where('previous_concept_id', $id)->get();
        if(count($next_concept) > 0){
            return $next_concept[0];
        } else {
            return null;
        }
    }

    public function deepCopy()
    {
        $new_course = new Course();
        $new_course->name = $this->name;
        $new_course->open_date = $this->open_date;
        $new_course->close_date = $this->close_date;

        return $new_course;
    }
}
