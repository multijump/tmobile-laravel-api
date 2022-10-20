<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>TMOEvents - New User Registration for Approval</title>
        <style type="text/css">
            
            body {
                font-family: Arial;
                font-size: 16px;
                line-height: 1.2;
                color: #000;
                background-color: #ffffff;
            }

            a {
            	color: #e20074;
            }         

        </style>
    </head>

	<body>
		<div style="background-color: #e20074; text-align: center;">&nbsp;<br><img src="{{ url('/') }}/png/t-mobile.png" width="200" style="width: 200px;" alt="T-Mobile"><br>&nbsp;</div>
		<p>Please review the Newly registered user at {{ $newUserEmail }}. If you approve access, please click “Approve” below and an acceptance message will be sent to them. If not approved, please click “Denied” and an automated email will be sent asking the user to contact <a href="mailto:{{ env('USER_SUPPORT_EMAIL_ADDRESS') }}">{{ env('USER_SUPPORT_EMAIL_ADDRESS') }}.</a></p>
		<p><a href="{{ route('register.approve', $uuid) }}">Approve</a>&nbsp;&nbsp;<a href="{{ route('register.deny', $uuid) }}">Deny</a></p>
	</body>
</html>

