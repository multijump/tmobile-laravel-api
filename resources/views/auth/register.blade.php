@extends('layouts.app')

@section('content')
<div class="guest-container">

    <div class="login-form-container">
        <h1>Sign Up</h1>
        <p>Please register with your T-Mobile address. No other email addresses will be allowed.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus placeholder="First Name">

                @if ($errors->has('first_name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
                @endif
            </div>

            <div>
                <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}"  data-required="true" autofocus placeholder="Last Name">

                @if ($errors->has('last_name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                @endif
            </div>

            <div>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" data-required="true" placeholder="Email Address" data-error=" *Please enter a valid t-mobile.com address.">

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
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
            </div>

            <div>
                <button type="submit" class="btn btn-primary btn-tmobile">{{ __('Submit') }}</button> <button type="submit" class="btn btn-primary btn-tmobile"><a href="{{ route('login') }}">{{ __('Cancel') }}</a></button>
            </div>
        </form>

    </div>
</div>
@endsection
