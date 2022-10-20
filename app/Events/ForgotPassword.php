<?php


namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class ForgotPassword
{
    use SerializesModels;

    public $user;
    public $code;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param $code
     * @return void
     */
    public function __construct(User $user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }
}
