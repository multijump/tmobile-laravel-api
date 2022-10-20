<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to TMOEvents</title>
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
<p>Welcome to TMOEvents! Please click the link below to verify your email address:</p>
<p><a href="{{route('email.verification', $uuid)}}">Confirm Email</a></p>
</body>
</html>
