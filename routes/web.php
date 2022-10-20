<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $requestUser = \Auth::user();

    if($requestUser) {
        if($requestUser->hasAdminRole()) {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('home');
        }
    }

    return redirect()->route('login');
});

/**
 * Authentication
 */
Route::get('/login', [
    'as' => 'login',
    'uses' => 'Auth\LoginController@showLoginForm'
]);

Route::post('login', [
    'as' => 'login_post',
    'uses' => 'Auth\LoginController@login'
]);

Route::post('logout', [
    'as' => 'logout',
    'uses' => 'Auth\LoginController@logout'
]);

/**
 * Password Set/Reset
 */
Route::post('password/email', [
    'as' => 'password.email',
    'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);

Route::post('password/reset', [
    'as' => '',
    'uses' => 'Auth\ResetPasswordController@reset'
]);

Route::get('password/set/{uniqueKey}', [
    'as' => 'password.set',
    'uses' => 'UserController@showSetPasswordForm'
]);

Route::post('password/set/{uniqueKey}', [
    'as' => 'password.set.store',
    'uses' => 'UserController@setPasswordStore'
]);

Route::get('password/reset/request/form', [
    'as' => 'password.reset.request.form',
    'uses' => 'UserController@resetPasswordRequestForm'
]);

Route::post('password/reset', [
    'as' => 'password.reset.store',
    'uses' => 'UserController@resetPasswordStore'
]);

/**
 * Email verification
 */
Route::get('email/verification/{uniqueKey}', [
    'as' => 'email.verification',
    'uses' => 'UserController@verifyEmail'
]);

/**
 * Registration
 */
Route::get('register', [
    'as' => 'register',
    'uses' => 'Auth\RegisterController@showRegistrationForm'
]);
Route::post('register', [
    'as' => '',
    'uses' => 'Auth\RegisterController@register'
]);

Route::get('register/decline/{uniqueKey}', [
    'as' => 'register.decline',
    'uses' => 'UserController@decline'
]);

/**
 * Store User
 */
Route::get('home', [
    'as' => 'home',
    'uses' => 'HomeController@index',
    'middleware' => ['auth.user:user']
]);

Route::get('users/{id}', [
    'as' => 'users.edit',
    'uses' => 'UserController@edit',
    'middleware' => ['auth.user:user']
]);

Route::post('users/{id}', [
    'as' => 'users.update',
    'uses' => 'UserController@update',
    'middleware' => ['auth.user:user']
]);

/**
 * Admin User Routes
 */
Route::get('admin/home', [
    'as' => 'admin.home',
    'uses' => 'AdminController@index',
    'middleware' => ['auth.admin:admin']
]);

Route::get('admin/users', [
    'as' => 'admin.users',
    'uses' => 'AdminController@users',
    'middleware' => ['auth.admin:admin']
]);

Route::get('admin/clients', [
    'as' => 'admin.clients',
    'uses' => 'AdminController@clients',
    'middleware' => ['auth.admin:admin']
]);

Route::post('admin/users/approve', [
    'as' => 'admin.users.approve',
    'uses' => 'AdminController@approveAdmin',
    'middleware' => ['auth.admin:admin']
]);

/**
 * User Approval/Denial
 */
Route::get('register/deny/{uuid}', [
    'as' => 'register.deny',
    'uses' => 'UserController@denyUser'
]);

Route::get('register/approve/{uuid}', [
    'as' => 'register.approve',
    'uses' => 'UserController@approveUser'
]);

/**
 * Events
 */
Route::get('events/create', [
    'as' => 'events.create',
    'uses' => 'EventController@create',
    'middleware' => ['auth.user:user']
]);

Route::post('events/create', [
    'as' => 'events.store',
    'uses' => 'EventController@store',
    'middleware' => ['auth.user:user']
]);

Route::get('events/edit/{id}', [
    'as' => 'events.edit',
    'uses' => 'EventController@edit',
    'middleware' => ['auth.user:user']
]);

Route::post('events/edit/{id}', [
    'as' => 'events.update',
    'uses' => 'EventController@update',
    'middleware' => ['auth.user:user']
]);

Route::post('events/archive/', [
    'as' => 'events.archive',
    'uses' => 'EventController@archive',
    'middleware' => ['auth.user:user']
]);

Route::get('events/winners/{id}', [
    'as' => 'events.winners.display',
    'uses' => 'EventController@showWinners',
    'middleware' => ['auth.user:user']
]);

Route::post('events/winners/select', [
    'as' => 'events.winners.select',
    'uses' => 'EventController@winnersSelect',
    'middleware' => ['auth.user:user']
]);

// Participants
Route::get('participants/register/{id}', [
    'as' => 'events.participants.register',
    'uses' => 'ParticipantController@register',
    'middleware' => ['auth.user:user']
]);

Route::get('participants/public/register/{id}', [
    'as' => 'events.participants.public.register',
    'uses' => 'ParticipantController@publicRegister'
]);

/**
 * Surveys
 */
Route::get('surveys/create', [
    'as' => 'surveys.create',
    'uses' => 'SurveyController@create',
    // 'middleware' => ['auth.user:user']
]);

Route::post('surveys/create', [
    'as' => 'surveys.store',
    'uses' => 'SurveyController@store',
    // 'middleware' => ['auth.user:user']
]);

/**
 * QR
 */
Route::get('qr/print/{id}', [
    'as' => 'qr.print',
    'uses' => 'EventController@qr',
    // 'middleware' => ['auth.user:user']
]);

Route::get('qr/quickprint/{id}', [
    'as' => 'qr.quickprint',
    'uses' => 'EventController@sendQREmail',
    // 'middleware' => ['auth.user:user']
]);

// Route::get('surveys/edit/{id}', [
//     'as' => 'surveys.edit',
//     'uses' => 'EventController@edit',
//     'middleware' => ['auth.user:user']
// ]);

// Route::post('surveys/edit/{id}', [
//     'as' => 'surveys.update',
//     'uses' => 'EventController@update',
//     'middleware' => ['auth.user:user']
// ]);


/**
 * Reports
 */
Route::post('reports/event', [
    'as' => 'reports.event',
    'uses' => 'ReportController@eventGenerate',
    'middleware' => ['auth.user:user']
]);

Route::post('reports/survey', [
    'as' => 'reports.survey',
    'uses' => 'ReportController@surveyGenerate',
    'middleware' => ['auth.user:user']
]);

Route::get('admin/reports/historical', [
    'as' => 'reports.historical',
    'uses' => 'ReportController@historical',
    'middleware' => ['auth.admin:admin']
]);

Route::post('admin/reports/historical', [
    'as' => 'reports.historical.generate',
    'uses' => 'ReportController@historicalGenerate',
    'middleware' => ['auth.admin:admin']
]);

Route::get('admin/reports/historicalsurvey', [
    'as' => 'reports.historicalsurvey',
    'uses' => 'ReportController@historicalsurvey',
    'middleware' => ['auth.admin:admin']
]);

Route::post('admin/reports/historicalsurvey', [
    'as' => 'reports.historicalsurvey.generate',
    'uses' => 'ReportController@historicalSurveyGenerate',
    'middleware' => ['auth.admin:admin']
]);

/**
 * AJax Datatables
 */
Route::get('admin/datatables/events', [
    'as' => 'datatables.events',
    'uses' => 'DatatablesController@events'
]);
Route::get('admin/datatables/users', [
    'as' => 'datatables.users',
    'uses' => 'DatatablesController@users'
]);
Route::get('admin/datatables/clients', [
    'as' => 'datatables.clients',
    'uses' => 'DatatablesController@clients'
]);

/**
 * Tool Guide Download (currently unused)
 */
Route::get('tool/download', [
    'as' => 'tool.download',
    'uses' => 'UserController@downloadToolGuide'
]);
