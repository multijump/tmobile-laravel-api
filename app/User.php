<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'unique_key', 'api_token', 'is_mobile', 'code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'accepted_date' => 'date:m/d/Y',
        'declined_date' => 'date:m/d/Y',
        'approved_date' => 'date:m/d/Y',
        'denied_date' => 'date:m/d/Y',
        'created_at' => 'date:m/d/Y',
    ];


    // Roles
    public function hasAdminRole()
    {
        return $this->is_admin;
    }

    // Approval Process
    public function hasAccepted()
    {
        return !is_null($this->accepted_date);
    }

    public function hasDeclined()
    {
        return !is_null($this->declined_date);
    }

    public function hasBeenApproved()
    {
        return !is_null($this->approved_date);
    }

    public function hasBeenDenied()
    {
        return !is_null($this->denied_date);
    }

    public function registeredParticipants()
    {
        return $this->hasMany('App\Models\Participant', 'created_by_id');
    }

    public function selectedParticipants()
    {
        return $this->hasMany('App\Models\Participant', 'selected_by_id');
    }

    public function createdEvents()
    {
        return $this->hasMany('App\Models\Event', 'created_by_id');
    }
}
