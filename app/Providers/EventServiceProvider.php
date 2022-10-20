<?php

namespace App\Providers;

use App\Events\UserApproved;
use App\Events\UserDenied;
use App\Events\UserRequestedPasswordReset;
use App\Events\UserWelcome;
use App\Events\ForgotPassword;
use App\Listeners\SendApprovedNotification;
use App\Listeners\SendDeniedNotification;
use App\Listeners\SendPasswordResetNotification;
use App\Listeners\SendWelcomeNotification;
use App\Listeners\SendForgotPasswordNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendRegisterVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendRegisterVerificationNotification::class,
        ],
        UserApproved::class => [
            SendApprovedNotification::class,
        ],
        UserDenied::class => [
            SendDeniedNotification::class,
        ],
        UserRequestedPasswordReset::class => [
            SendPasswordResetNotification::class,
        ],
        UserWelcome::class => [
            SendWelcomeNotification::class,
        ],
        ForgotPassword::class => [
            SendForgotPasswordNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
