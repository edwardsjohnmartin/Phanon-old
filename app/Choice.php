<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    // Table Name
    public $table = 'choice_exercises';

    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = false;

    public function exercise()
    {
        return $this->morphOne('App\Exercise', 'type');
    }

    public function choicesAsArray()
    {
        return json_decode($this->choices);
    }

    public function solutionText()
    {
        return $this->choicesAsArray()[$this->solution];
    }
}
