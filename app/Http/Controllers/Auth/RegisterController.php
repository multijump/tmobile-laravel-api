<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserApproved;
use App\Events\UserDenied;
use App\Http\Requests\RecaptchaFormRequest;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // TODO UPDATE AFTER TESTING
        $messages = [
          'email.required' => 'Please enter a valid t-mobile.com email address.'
//          'email.regex' => 'Please enter a valid t-mobile.com email address.'
        ];

        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
//                'regex:/t-mobile.com/i'

            ]
        ],$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     * @throws Exception
     */
    protected function create(array $data)
    {

        //previously, this wwas using uniqid(). DO NOT USE UNIQID FOR THINGS THAT NEED TO BE CRYPTOGRAPHICALLY SECURE.
        $password = bin2hex(random_bytes(8));
        $uniqueKey = bin2hex(random_bytes(8));
        $apiKey = bin2hex(random_bytes(8));

        $newUser =  User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'unique_key' => $uniqueKey,
            'api_token' => Hash::make($apiKey),
        ]);

        //case-insensitive check if email is @t-mobile.com
        //if so, automatically approve

        if(substr_compare(strtolower($data['email']), strtolower(env('ALLOWED_DOMAIN')), -strlen(env('ALLOWED_DOMAIN'))) == 0){
            $now = new Carbon();
            $newUser->approved_date = $now;
            $newUser->accepted_date = $now;
            $newUser->save();
        }

        return $newUser;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param RecaptchaFormRequest $request
     * @return Response
     */
    public function register(RecaptchaFormRequest $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        event(new Registered($user));
        if(substr_compare(strtolower($user->email), strtolower(env('ALLOWED_DOMAIN')), -strlen(env('ALLOWED_DOMAIN'))) == 0){
            event(new UserApproved($user));
        }

        $this->guard()->logout();

        if (!Session::has('errorMessage')) {
            if(substr_compare(strtolower($user->email), strtolower(env('ALLOWED_DOMAIN')), -strlen(env('ALLOWED_DOMAIN'))) == 0){
                Session::flash('successMessage', 'Thank you for registering. You have been provided access on the basis of your T-Mobile-domain email address.');
            }else{
                Session::flash('successMessage', 'Thank you for registering. Please check your email to see if you have been provided access. Please note, this is NOT an automated system.');
            }
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
