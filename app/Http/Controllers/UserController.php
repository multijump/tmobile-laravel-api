<?php

namespace App\Http\Controllers;

use App\Events\UserApproved;
use App\Events\UserDenied;
use App\Events\UserRequestedPasswordReset;
use App\Events\UserWelcome;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Show the event edit form.
     *
     * @return Response
     */
    public function edit($id)
    {
        $requestUser = \Auth::user();

        $user = User::find($id);
        if (empty($user)) {
            return $this->redirectWithMessage( 'errorMessage', 'Could Not Find User.');
        }

        if ($requestUser->id !== $user->id) {
            return $this->redirectWithMessage( 'errorMessage', 'Profile can only be edited by user.');
        }

        return view('users.edit')->with(['user' => $user]);
    }

    /**
     * Update the user.
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $requestUser = \Auth::user();
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $email = $request->get('email');
        $password = trim($request->get('password'));
        $passwordConfirm = trim($request->get('password_confirmation'));
        $user = User::find($id);

        if (empty($requestUser)) {
            return $this->redirectWithMessage( 'errorMessage', 'Please login.', null, 'login');
        }
        if (empty($user)) {
            return $this->redirectWithMessage( 'errorMessage', 'Could Not Find User.', $request);
        }
        if ($requestUser->id !== $user->id) {
            return $this->redirectWithMessage( 'errorMessage', 'Profile can only be edited by user.', $request);
        }

        $validationMessages = [
            'first_name.required' => 'First name is required',
            'first_name.max' => 'First name can only be 255 characters',
            'last_name.required' => 'Last name is required',
            'last_name.max' => 'Last name can only be 255 characters',
        ];

        $validationRules = [
            'first_name' => [
                'required',
                'string',
                'max:255'
            ],
            'last_name' => [
                'required',
                'string',
                'max:255'
            ]
        ];

        if ($email !== $requestUser->email) {
            $validationMessages['email.required'] = 'Email is required.';
            $validationMessages['email.unique'] = 'Email is currently used by another user.';
            $validationMessages['email.regex'] = 'Email must be a valid t-mobile.com email address.';

            $validationRules['email'] = [
                'required',
                'string',
                'email',
                'unique:users',
                'max:255',
                'regex:/t-mobile.com/i'
            ];
        }

        if (!empty($password) || !empty($passwordConfirm)) {

            $validationMessages['password.required'] = 'Password is required';
            $validationMessages['password.max'] = 'Password must be at less than 255 characters.';
            $validationMessages['password.min'] = 'Password must be at least 8 characters.';
            $validationMessages['password.regex'] = 'Password must contain: * One uppercase character (A-Z) * One lowercase character (a-z). * One number (0-9) * A special character (~!@#$%^&*_-+=`|\(){}[]:;"\'<>,.?/).';

            $validationRules['password'] = [
                'required',
                'confirmed',
                'string',
                'max:255',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ];
        }

        $request->validate($validationRules, $validationMessages);

        $user->email = $email;
        $user->first_name = $firstName;
        $user->last_name = $lastName;

        // Password was updated and already validated
        if (!empty($password) || !empty($passwordConfirm)) {
            $user->password = Hash::make($password);
        }

        $user->save();

        if($requestUser->hasAdminRole()) {
            $redirectPath = route('admin.home');
        } else {
            $redirectPath = route('home');
        }

        return $this->redirectWithMessage(
            'successMessage',
            'Your profile has been updated.',
            null,
            $redirectPath
        );
    }

    public function showSetPasswordForm(Request $request, $uniqueKey)
    {
        $requestUser = \Auth::user();
        $user = User::where('unique_key', $uniqueKey)->first();

        // User is already logged in
        if(!empty($requestUser)) {
            return redirect(route('users.edit', $requestUser->id));
        }

        if(empty($user)) {
            return $this->redirectWithMessage(
                'errorMessage',
                'This user token has expired. Please contact email sender.',
                null,
                null,
                'login'
            );
        }

        return view('auth.setPassword')->with(['user' => $user]);
    }

    public function setPasswordStore(Request $request, $uniqueKey)
    {
        $user = User::where('unique_key', $uniqueKey)->first();
        $email = $request->get('email');

        if(empty($user)) {
            return $this->redirectWithMessage(
                'errorMessage',
                'Could not find user,
                please contact email sender.',
                null,
                null,
                'login'
            );
        }

        if (empty($email)) {
            Session::flash('errorMessage', 'Email is required.');
            return redirect()->back();
        }

        if(strcasecmp($user->email, $email) != 0) {
            Session::flash(
                'errorMessage',
                'Please check that you have the correct email address. It will be the one that contained this link.'
            );
            return redirect()->back();
        }

        $messages = [
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password and confirmation do not match',
            'password.max' => 'Password must be at less than 255 characters.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => "Password must contain: * One uppercase character (A-Z) * One lowercase character (a-z). * One number (0-9) * A special character (~!@#$%^&*_-+=`|\(){}[]:;\"'<>,.?/)",
        ];

        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'string',
                'max:255',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ]
        ], $messages);

        $now = new Carbon();
        $user->password = Hash::make($request->get('password'));
        $user->accepted_date = $now;
        $user->declined_date = null;
        $user->unique_key = bin2hex(random_bytes(8));
        $user->save();

        auth()->login($user);

        if($user->hasAdminRole()) {
            $redirectPath = route('admin.home');
        } else {
            $redirectPath = route('home');
        }

        return $this->redirectWithMessage(
            'successMessage',
            'Your profile has been updated.',
            null,
            $redirectPath
        );
    }

    public function resetPasswordRequestForm(Request $request)
    {
        $requestUser = \Auth::user();

        // User is already logged in
        if(!empty($requestUser)) {

            Session::flash(
                'errorMessage',
                'User is currently logged in.'
            );

            if($requestUser->hasAdminRole()) {
                $redirectPath = route('admin.home');
            } else {
                $redirectPath = route('home');
            }

            return redirect($redirectPath);
        }

        return view('auth.resetPasswordRequestForm');
    }

//    public function decline(Request $request, $uniqueKey)
//    {
//        $user = User::where('unique_key', $uniqueKey)->first();
//
//        if(empty($user)) {
//            return $this->redirectWithMessage(
//                'errorMessage',
//                'Could not find user,
//                please contact email sender if you found this in error.',
//                null,
//                null,
//                'login'
//            );
//        }
//
//        if (!is_null($user->accepted_date)) {
//            return $this->redirectWithMessage(
//                'errorMessage',
//                'User has already accepted.',
//                null,
//                null,
//                'login'
//            );
//        }
//
//        $now = new Carbon();
//        $user->declined_date = $now;
//
//        $user->save();
//
//        return view('users.decline');
//    }

    public function verifyEmail($uuid) {
        try {
            $user = User::where('unique_key', $uuid)->first();

            if (empty($user)) {
                $errorMessage = 'Could not find user,
                please contact email sender.';
                $messageType = 'errorMessage';
                if ($requestUser = \Auth::user()) {
                    $errorMessage = '';
                    $messageType = '';
                }
                return $this->redirectWithMessage(
                    $messageType,
                    $errorMessage,
                    null,
                    'home'
                );
            }

            if ($user->is_mobile) {
                $now = new Carbon();
                $user->approved_date = $now;
                $user->unique_key = bin2hex(random_bytes(8));
                $user->save();

                event(new UserWelcome($user));
            } else {
                event(new UserApproved($user));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $this->redirectWithMessage(
            'successMessage',
            'Your email has been verified.',
            null,
            'login'
        );
    }

    private function redirectWithMessage($messageType, $messageText, $request = null, $redirectPath = '')
    {
        Session::flash($messageType, $messageText);
        if(!empty($request)) {
            $request->flash();
        }

        if(!empty($redirectPath)) {
            return redirect($redirectPath);
        }
        return redirect()->back();
    }

    public function downloadToolGuide()
    {
        return response()->download(public_path('pdf/Lead_Gathering_Tool_Instructions_Final.pdf'));

    }


    /**
     * Approve a registered user for the application.
     *
     * @param $uuid
     * @return Response
     * @throws Exception
     */
    public function approveUser($uuid)
    {
        $redirectRoute = 'login';

        $requestUser = \Auth::user();

        if (!empty($requestUser)) {
            if ($requestUser->hasAdminRole()) {
                $redirectRoute = 'admin.home';
            } else {
                $redirectRoute = 'home';
            }
        }

        $user = User::where('unique_key', $uuid)->first();

        if(empty($user)) {
            Session::flash('errorMessage', 'Could not find user,
                please contact email sender if you found this in error.');
            return redirect()->route($redirectRoute);
        }

        if (!is_null($user->approved_date)) {
            Session::flash('errorMessage', 'This user was approved on ' . $user->approved_date->format('m/d/Y'));
            return redirect()->route($redirectRoute);
        }

        if (!is_null($user->denied_date)) {
            Session::flash('errorMessage', 'This user was denied on ' . $user->denied_date->format('m/d/Y'));
            return redirect()->route($redirectRoute);
        }

        $now = new Carbon();
        $user->approved_date = $now;
        $user->unique_key = bin2hex(random_bytes(8));
        $user->save();

        Session::flash('successMessage', 'User has been approved and email has been sent to ' . $user->email);

        if ($user->is_mobile) {
            event(new UserWelcome($user));
        } else {
            event(new UserApproved($user));
        }

        return redirect()->route($redirectRoute);
    }


    /**
     * Deny a registered user for the application.
     *
     * @param   $uuid
     * @return Response
     * @throws Exception
     */
    public function denyUser($uuid)
    {

        $redirectRoute = 'login';

        $requestUser = \Auth::user();

        if (!empty($requestUser)) {
            if ($requestUser->hasAdminRole()) {
                $redirectRoute = 'admin.home';
            } else {
                $redirectRoute = 'home';
            }
        }

        $user = User::where('unique_key', $uuid)->first();

        if(empty($user)) {
            Session::flash('errorMessage', 'Could not find user,
                please contact email sender if you found this in error.');
            return redirect()->route($redirectRoute);
        }

        if (!is_null($user->approved_date)) {
            Session::flash('errorMessage', 'This user was approved on ' . $user->approved_date->format('m/d/Y'));
            return redirect()->route($redirectRoute);
        }

        if (!is_null($user->denied_date)) {
            Session::flash('errorMessage', 'This user was denied on ' . $user->denied_date->format('m/d/Y'));
            return redirect()->route($redirectRoute);
        }

        $now = new Carbon();
        $user->denied_date = $now;
        $user->unique_key = bin2hex(random_bytes(8));
        $user->save();

        event(new UserDenied($user));

        Session::flash('successMessage', 'User ' .  $user->last_name . ', ' . $user->first_name . ' has been denied and an email has been sent to ' . $user->email);

        return redirect()->route($redirectRoute);
    }
}
