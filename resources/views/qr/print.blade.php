@extends('layouts.app')

@section('content')
<div class="container" style="text-align: center">
  <h1 class="print-hide" id="qrcode-label">Scan QR Code</h2>
  <br>
  <script src="/js/qrcode.min.js"></script>
  <div id="qrcode" class="qrcode"></div>
  <div class="qrcode-event-name force-print-white" style="font-size: 13px; position: absolute; top: calc(50% + 400px);left:0px;width: 100%; text-align: center">{{$event->title}}</div>
  <script type="text/javascript">
    new QRCode(document.getElementById("qrcode"), window.location.origin + "/participants/public/register/{{ $event->id }}");
    document.getElementById("qrcode").getElementsByTagName("img")[0].style.margin="auto";
  </script>
</div>
@endsection