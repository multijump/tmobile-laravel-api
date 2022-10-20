<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>TMO Events – Access Denied</title>
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
		<p>At this time we're only allowing users with T-Mobile email addresses to gain access to the site. If you are a T-Mobile employee, please register again for the tool using your T-Mobile email address. If you are an agency or partner of T-Mobile and need access to the site, please contact <a href="mailto:{{ env('USER_SUPPORT_EMAIL_ADDRESS') }}">{{ env('USER_SUPPORT_EMAIL_ADDRESS') }}</a> with additional information on why you need access to the site.</p>
        <br>
        <p>If you are a customer trying to register to win a prize or contest; you were mistakenly signed up to become a user to this site and not entered to win. Please contact <a href="mailto:{{ env('USER_SUPPORT_EMAIL_ADDRESS') }}">{{ env('USER_SUPPORT_EMAIL_ADDRESS') }}</a> with details of the event/prize you were trying to win so that we can enter your information correctly.</p>
	</body>
</html>
