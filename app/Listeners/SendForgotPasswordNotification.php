<?php


namespace App\Listeners;

use App\Events\ForgotPassword;
use Illuminate\Support\Facades\Session;
use Mandrill;
use Mandrill_Error;

class SendForgotPasswordNotification
{
    /**
     * Handle the event.
     *
     * @param ForgotPassword $event
     * @throws \Mandrill_Error
     * @return void
     */
    public function handle(ForgotPassword $event)
    {
        // Send email to notify user they were approved
        try {
            $code = $event->code;
            $mandrill = new Mandrill(env('MANDRILL_API_KEY'));
            $message = array(
                'html' => view('emails.forgotPassword', ['code' => $code])->render(),
                'subject' => 'Forgot Password',
                'from_email' => env('EMAIL_FROM_ADDRESS'),
                'from_name' => env('EMAIL_FROM_NAME'),
                'to' => array(
                    array(
                        'email' => $event->user->email,
                        'name' => 'Forgot Password',
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => env('EMAIL_FROM_ADDRESS'))
            );

            $result = $mandrill->messages->send($message);

            if (!empty($result[0]) && !empty($result[0]['status'])) {
                if($result[0]['status'] == 'rejected')
                {
                    Session::flash(
                        'errorMessage',
                        'There was a problem sending the request password email, please try again later.'
                    );
                }
            }

        } catch(Mandrill_Error $e) {
            Session::flash('errorMessage', 'There was a problem sending the request password email, please try again later.');
        }
    }
}
