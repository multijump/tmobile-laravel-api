<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Event Winners Export</title>
        <style type="text/css">
            
            body {
                font-family: Arial;
                font-size: 16px;
                line-height: 1.2;
                color: #000;
            }

            a {
            	color: #e20074;
            }

            .fixed-center {
                position: absolute;
                overflow: visible;
                left: 0;
                right: 0;
                top: 0;
                bottom: 0;
                width: 279mm;
                height: 216mm;
                margin-top: auto;
                margin-bottom: auto;
                margin-left: auto;
                margin-right: auto;
            }

            .fixed-offset-left {
                position: absolute;
                overflow: visible;
                left: 0;
                right: 139.5mm;
                top: 50mm;
                bottom: 0;
                width: 256px;
                height: 256px;
                margin-top: auto;
                margin-bottom: auto;
                margin-left: auto;
                margin-right: auto;
            }

            .fixed-offset-right {
                position: absolute;
                overflow: visible;
                left: 139.5mm;
                right: 0;
                top: 50mm;
                bottom: 0;
                width: 256px;
                height: 256px;
                margin-top: auto;
                margin-bottom: auto;
                margin-left: auto;
                margin-right: auto;
            }

            .fixed-offset-bottom-left {
                position: absolute;
                overflow: visible;
                left: 0;
                right: 139.5mm;
                top: 265mm;
                bottom: 0;
                width: 130mm;
                text-align: center;
                height: 256px;
                margin-top: auto;
                margin-bottom: auto;
                margin-left: auto;
                margin-right: auto;
                color: #231f20;
            }

            .fixed-offset-bottom-right {
                position: absolute;
                overflow: visible;
                left: 139.5mm;
                right: 0;
                top: 265mm;
                bottom: 0;
                width: 130mm;
                text-align: center;
                height: 256px;
                margin-top: auto;
                margin-bottom: auto;
                margin-left: auto;
                margin-right: auto;
                color: #231f20;
            }

        </style>
    </head>

	<body>
        <div class="fixed-center" style="color: blue; background-image: url('https://www.tmoevents.com/png/print-bg-landscape.jpg'); background-size: 100% 100%"></div>
        <div class="fixed-offset-left" style="color: blue; background-image: url('https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=https://www.tmoevents.com/participants/public/register/{{$eventId}}'); background-size: 100% 100%"></div>
        <div class="fixed-offset-right" style="color: blue; background-image: url('https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=https://www.tmoevents.com/participants/public/register/{{$eventId}}'); background-size: 100% 100%"></div>
        <div class="fixed-offset-bottom-left">{{$eventName}}</div>
        <div class="fixed-offset-bottom-right">{{$eventName}}</div>
	</body>
</html>

