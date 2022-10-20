<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'example_question', 'event_id'
    ];

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }
}
