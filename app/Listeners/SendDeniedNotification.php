<?php

namespace App\Listeners;


use App\Events\UserDenied;
use Illuminate\Support\Facades\Session;
use Mandrill;
use Mandrill_Error;


class SendDeniedNotification
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserDenied  $event
     * @throws \Mandrill_Error
     * @return void
     */
    public function handle(UserDenied $event)
    {
        // Send email to notify user they were denied

        try {

            $uuid = $event->user->unique_key;
            $mandrill = new Mandrill(env('MANDRILL_API_KEY'));
            $message = array(
                'html' => view('emails.userDenied', ['uuid' => $uuid])->render(),
                'subject' => 'TMO Events – Access Denied',
                'from_email' => env('EMAIL_FROM_ADDRESS'),
                'from_name' => env('EMAIL_FROM_NAME'),
                'to' => array(
                    array(
                        'email' => $event->user->email,
                        'name' => 'Register',
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => env('EMAIL_FROM_ADDRESS')),
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
