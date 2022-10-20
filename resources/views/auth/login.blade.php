@extends('layouts.app')

@section('content')
<div class="guest-container">
    <div class="row">
        <div class="col-md-12">
            <p style="text-align: center">Welcome to TMO Events!</p>
            <br>
            <p>This tool allows your team to electronically gather customer and prospect information while at events. This gets rid of our "fishbowl" drawings because the tool can choose winners for your contests, sweepstakes and giveaways.</p>
            <div class="center-block text-center">
                <a href="mailto:{{ env('USER_SUPPORT_EMAIL_ADDRESS') }}" class="btn btn-primary btn-tmobile" style="padding: 1em">Support</a>
                <a href="{{ url('/') }}/pdf/Tool_Guide.pdf"  target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-tmobile" style="padding: 1em">Tool Guide</a>
                <a href="{{ url('/') }}/pdf/Sweepstakes_Rules_Combined.pdf" style="padding: .5em" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-tmobile">Sweepstakes Rules<BR>(Must Print)</a>
            </div>
        </div>
    </div>

    <div class="login-form-container">

        <h1>Sign In</h1>


        <form method="POST" action="{{ route('login_post') }}">
            @csrf
            <input type="hidden" name="previousUrl" value="{{ $previousUrl }}">
            <div>

                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Address">

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif

            </div>

            <div>

                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif

            </div>

            <div style="display: flex; flex-direction: column; align-items: center;">
                <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_SITE_KEY')}}"></div>
                @if ($errors->has('g-recaptcha-response'))
                    <span class="invalid-feedback" role="alert" style="display: block;">
                        <strong>{{ trans('validation.recaptcha') }}</strong>
                    </span>
                @endif
                <br>

                <a class="forgot-password" href="{{ route('password.reset.request.form') }}"> {{ __('Forgot Password?') }}</a>

                <br><br>

                <button type="submit" class="btn btn-primary btn-tmobile">
                    {{ __('Login') }}
                </button>
                <div class="or">&mdash; or &mdash;</div>
                <a href="register" class="btn-tmobile">Sign Up</a>
            </div>
        </form>

        <p class="login-footer"><a href="{{ env('PRIVACY_POLICY_LINK') }}">Privacy Policy</a></p>

    </div>
</div>
@endsection
