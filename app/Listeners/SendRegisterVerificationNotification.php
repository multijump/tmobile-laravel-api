<?php

namespace App\Listeners;


use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Mandrill;
use Mandrill_Error;
use Illuminate\Support\Facades\Log;


class SendRegisterVerificationNotification
{

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @throws \Exception
     * @throws \Mandrill_Error
     * @return void
     */
    public function handle(Registered $event)
    {
        // Send email to notify T-Mobile of user's registration

        // don't send email if it's a t-mobile email address
        if(substr_compare(strtolower($event->user->email), strtolower("@T-Mobile.com"), -strlen("@T-Mobile.com")) == 0){
            return;
        }


        // TODO In the future, we may want a warning email sent to admins if any of these exceptions are thrown.
        try {
            $confirmationEmailAddressesConfig = env('USER_CONFIRMATION_EMAIL_ADDRESSES');
            Log::info('confirm email : ' . $confirmationEmailAddressesConfig);

            if (empty($confirmationEmailAddressesConfig)) {
                throw new \Exception('There was an issue registering, please try again later.');
            }

            $confirmationEmailAddresses = explode(',', $confirmationEmailAddressesConfig);

            if (empty($confirmationEmailAddresses) || !is_array($confirmationEmailAddresses)) {
                throw new \Exception('There was an issue registering, please try again later.');
            }

            foreach ($confirmationEmailAddresses as $confirmationEmailAddress) {
                if (!filter_var($confirmationEmailAddress, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception('There was an issue registering, please try again later.');
                }
            }

        } catch (\Exception $exception) {
            Log::info('Exception : ' . $exception->getMessage());
            Session::flash('errorMessage', $exception->getMessage());
            return redirect('login');
        }

        try {
            $uuid = $event->user->unique_key;
            Log::info('Mandrill Key : ' . env('MANDRILL_API_KEY'));
            $mandrill = new Mandrill(env('MANDRILL_API_KEY'));

            $emailIsSent = false;
            foreach ($confirmationEmailAddresses as $confirmationEmailAddress) {

                Log::info('from_email : ' . env('EMAIL_FROM_ADDRESS'));
                $message = array(
                    'html' => view('emails.userRegistered', ['uuid' => $uuid, 'newUserEmail' => $event->user->email])->render(),
                    'subject' => 'TMOEvents – New User Registration for Approval',
                    'from_email' => env('EMAIL_FROM_ADDRESS'),
                    'from_name' => env('EMAIL_FROM_NAME'),
                    'to' => array(
                        array(
                            'email' => $confirmationEmailAddress,
                            'name' => 'Register',
                            'type' => 'to'
                        )
                    ),
                    'headers' => array('Reply-To' => env('EMAIL_FROM_ADDRESS'))
                );
                Log::info(json_encode($message));

                $result = $mandrill->messages->send($message);

                if (empty($result[0]['reject_reason'])) {
                    $emailIsSent = true;
                    Log::info('Mail sent : ' . json_encode($result));
                }
            }

            if (!$emailIsSent) {
                Session::flash('errorMessage', 'There was a problem processing your request. Please try again or contact <a href="mailto:'
                    . env('USER_SUPPORT_EMAIL_ADDRESS') . '" >' . env('USER_SUPPORT_EMAIL_ADDRESS') . '</a>');
            }

            if ($event->user->is_mobile) {
                $message = [
                    'html' => view('emails.userConfirm', ['uuid' => $uuid])->render(),
                    'subject' => 'TMOEvents – Email Verification',
                    'from_email' => env('EMAIL_FROM_ADDRESS'),
                    'from_name' => env('EMAIL_FROM_NAME'),
                    'to' => array(
                        array(
                            'email' => $event->user->email,
                            'name' => 'Verification',
                            'type' => 'to'
                        )
                    ),
                    'headers' => array('Reply-To' => env('EMAIL_FROM_ADDRESS'))
                ];

                $result = $mandrill->messages->send($message);

                if (empty($result[0]['reject_reason'])) {
                    $emailIsSent = true;
                    Log::info('Mail sent : ' . json_encode($result));
                }
            }
        } catch(Mandrill_Error $e) {
            Log::info('Mandrill_Error : ' . $e->getMessage() . get_class($e));
            // Mandrill errors are thrown as exceptions
//            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
//            throw $e;
            Session::flash('errorMessage', 'There was a problem processing your request. Please try again or contact <a href="mailto:'
                . env('USER_SUPPORT_EMAIL_ADDRESS') . '" >' . env('USER_SUPPORT_EMAIL_ADDRESS') . '</a>');
        }
    }
}
