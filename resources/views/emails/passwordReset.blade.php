<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>TMOEvents - Reset Your Password</title>
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
		<p>Please click the below link to reset your password.</p>
		<p><a href="{{route('password.set', $uuid)}}">Reset Password</a></p>
	</body>
</html>
