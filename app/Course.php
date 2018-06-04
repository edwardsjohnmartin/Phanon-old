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

    /**
     * Relationship function
     * Returns an array of concepts contained in this course
     */
    public function unorderedConcepts()
    {
        return $concepts = $this->hasMany('App\Concept');
    }

    /**
     * Relationship function
     * Returns the user this course belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Returns an array of the concepts within the course in their correct order
     */
    public function concepts()
    {
        // Get the concepts that are in this course
        $concepts = $this->unorderedConcepts;

        // Create the array that will store the concepts in the correct order
        $ordered_concepts = array();

        if(count($concepts) > 0){
            // Get the first concept (its previous_concept_id is null) and put it into the array
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

    /**
     * Remove the concept from this course and retains the ordered of the remaining concepts
     */
    public function removeConcept($concept)
    {
        $next_concept = $this->nextConcept($concept->id);

        if(!is_null($next_concept)){
            $next_concept->previous_concept_id = $concept->previous_concept_id;
            $next_concept->save();
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
