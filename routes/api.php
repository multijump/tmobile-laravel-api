<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});



Route::post('participants/register/{id}', [
    'as' => 'events.participants.register.store',
    'uses' => 'ParticipantController@registerStore'
]);

Route::post('admin/users/delete', [
    'as' => 'admin.users.delete',
    'uses' => 'AdminController@deleteUser',
]);

Route::post('admin/client/delete', [
    'as' => 'admin.clients.delete',
    'uses' => 'AdminController@deleteClient',
]);

Route::post('admin/users/promote', [
    'as' => 'admin.users.promote',
    'uses' => 'AdminController@promoteAdmin',
]);

// V1 apis
Route::prefix('v1')->group(function () {
    Route::post('/user/register', 'Api\AuthController@register');
    Route::post('/user/login', 'Api\AuthController@login');
    Route::post('/user/resend/{uuid}', 'Api\AuthController@resend');
    Route::post('/user/forgotPassword', 'Api\AuthController@forgotPassword');
    Route::post('/user/confirmCode', 'Api\AuthController@forgotPasswordConfirm');

    Route::middleware('auth:api')->group(function () {
        Route::post('/user/logout', 'Api\AuthController@logout');
        Route::put('/user', 'Api\AuthController@update');
        Route::get('/user', 'Api\AuthController@get');

        // Participants apis
        Route::get('/participants/status/{status}', 'Api\ParticipantController@getParticipants');
        Route::get('/participants/{id}', 'Api\ParticipantController@get');
        Route::get('/participants', 'Api\ParticipantController@getAllParticipants');

        // Events apis
        Route::post('/events', 'Api\EventController@create');
        Route::put('/events/{id}', 'Api\EventController@update');
        Route::get('/events/current', 'Api\EventController@getCurrent');
        Route::get('/events/upcoming', 'Api\EventController@getUpcoming');
        Route::get('/events/{id}', 'Api\EventController@get');
    });
});



