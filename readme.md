# T-Mobile Event Registration

Registration App For T-Mobile Events

## Config Variables

* MANDRILL_API_KEY : Api key received from Mandrill.
* EMAIL_FROM_ADDRESS : Email address emails are sent from.
* EMAIL_FROM_NAME : Email display from name.
* USER_SUPPORT_EMAIL_ADDRESS : Email address for user support.
* ZIP_FILE_PASSWORD : Password to unzip emailed files.
* USER_CONFIRMATION_EMAIL_ADDRESSES : Comma Separated list of addresses to send confirmation emails when users are newly registered.

## How to Run the App Locally

1) Install PHP and Composer if you do not already have them installed.
2) Run the install command: `composer install`
3) When composer finishes installing, run the serve command to serve the app locally: `php artisan serve`
4) Your terminal will tell you where to find the app (typically http://localhost:8000).

## Artisan Commands
```
php artisan serve
```
Runs the application locally.



```
php artisan purge:participants
```
Update records for participants that signed up for events that have an end date of 90 days (or longer) prior to today. Email and phone number are changed to "XXXXXXX".

Currently set to run daily on Heroku Scheduler.