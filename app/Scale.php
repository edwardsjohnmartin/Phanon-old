<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scale extends Model
{
    // Table Name
    public $table = 'scale_exercises';

    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = false;

    public function exercise()
    {
        return $this->morphOne('App\Exercise', 'type');
    }

    public function labelsAsArray()
    {
        return json_decode($this->labels);
    }
}
