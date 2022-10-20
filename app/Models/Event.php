<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date:m/d/Y',
        'end_date' => 'date:m/d/Y'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'start_date', 'end_date', 'description', 'region', 'state', 'has_surveys_enabled', 'public',
        'workfront_id', 'company', 'is_archived'
    ];

    public function hasSurveysEnabled()
    {
        if ($this->has_surveys_enabled === 1) {
            return true;
        }

        return false;
    }

    public function isCompleted()
    {
        return $this->end_date < now();
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function participants()
    {
        return $this->hasMany('App\Models\Participant');
    }

    public function surveys()
    {
        return $this->hasMany('App\Models\Survey');
    }

    public function participantCount()
    {
        return $this->participants()->count();
    }

    public function winners()
    {
        return $this->participants()->whereNotNull('selected_date');
    }

    public function winnerCount()
    {
        return $this->winners()->count();
    }

    public function nonWinners()
    {
        return $this->participants()->whereNull('selected_date');
    }

    public function nonWinnerCount()
    {
        return $this->nonWinners()->count();
    }

    public function createdByUser()
    {
        return $this->belongsTo('App\User', 'created_by_id');
    }

    public function getDatePickerStartDate()
    {
        return $this->start_date->format('m/d/Y');
    }

    public function getDatePickerEndDate()
    {
        return $this->end_date->format('m/d/Y');
    }
}
