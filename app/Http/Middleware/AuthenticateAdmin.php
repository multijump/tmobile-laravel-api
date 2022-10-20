<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthenticateAdmin
{
    public function handle($request, Closure $next, $guard = null)
    {
        $loggedInUser = Auth::user();

        if(empty($loggedInUser)) {

            Session::flash('errorMessage', "Please Login");

            return redirect('login');
        }

        if($loggedInUser->hasBeenDenied()) {
            Session::flash('errorMessage', "User has already been submitted and was denied.");

            return redirect('login');
        }

        if(!$loggedInUser->hasBeenApproved()) {

            Session::flash('errorMessage', "User has already been submitted and is awaiting approval.");

            return redirect('login');
        }

//        if($loggedInUser->hasDeclined()) {
//
//            Session::flash('errorMessage', "User has already been submitted and declined the invitation.");
//
//            return redirect('login');
//        }
//
//        if(!$loggedInUser->hasAccepted()) {
//
//            Session::flash('errorMessage', "User has already been approved and is awaiting acceptance. Please check your email.");
//
//            return redirect('login');
//        }


        if(!$loggedInUser->hasAdminRole()) {

            Session::flash('errorMessage', "You do not have permission to visit this page.");

            return redirect('home');
        }


        return $next($request);
    }
}
