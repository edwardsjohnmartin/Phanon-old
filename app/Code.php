<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    // Table Name
    public $table = 'code_exercises';

    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = false;

    public function exercise()
    {
        return $this->morphOne('App\Exercise', 'type');
    }
}
