<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    // Table Name
    public $table = 'stats';

    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = false;

    public function projectProgress()
    {
        return $this->belongsTo('App\ProjectProgress');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
