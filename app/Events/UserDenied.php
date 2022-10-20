<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class UserDenied
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}