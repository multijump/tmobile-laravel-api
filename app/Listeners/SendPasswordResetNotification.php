<?php

namespace App\Listeners;


use Illuminate\Support\Facades\Session;
use Mandrill;
use Mandrill_Error;
use App\Events\UserRequestedPasswordReset;


class SendPasswordResetNotification
{

    /**
     * Handle the event.
     *
     * @param UserRequestedPasswordReset $event
     * @throws \Mandrill_Error
     * @return void
     */
    public function handle(UserRequestedPasswordReset $event)
    {
        // Send email to approved user to reset password

        try {

            $uuid = $event->user->unique_key;
            $mandrill = new Mandrill(env('MANDRILL_API_KEY'));
            $message = array(
                'html' => view('emails.passwordReset', ['uuid' => $uuid])->render(),
                'subject' => 'TMOEvents – Reset your password',
                'from_email' => env('EMAIL_FROM_ADDRESS'),
                'from_name' => env('EMAIL_FROM_NAME'),
                'to' => array(
                    array(
                        'email' => $event->user->email,
                        'name' => 'Register',
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => env('EMAIL_FROM_ADDRESS'))
            );

            $result = $mandrill->messages->send($message);

            if (!empty($result[0]) && !empty($result[0]['status'])) {
                if($result[0]['status'] == 'rejected')
                {
                    Session::flash('errorMessage', 'There was a problem sending the request password email, please try again later.');
                }
            }

        } catch(Mandrill_Error $e) {
            // Mandrill errors are thrown as exceptions
//            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
//            throw $e;
            Session::flash('errorMessage', 'There was a problem sending the request password email, please try again later.');
        }
    }
}
