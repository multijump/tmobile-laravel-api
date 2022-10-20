<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Participant extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'zip_code', 'language', 'selected_date', 'selected_by_id',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'selected_date' => 'date:m/d/Y'
    ];

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    /**
     * Scope to get participants by status
     *
     * @param Builder $query
     * @param string $status
     * @return mixed
     */
    public function scopeStatus(Builder $query, string $status) {
        return $query->where('status', $status);
    }
}
