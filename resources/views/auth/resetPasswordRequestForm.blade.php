@extends('layouts.app')

@section('content')
    <div class="guest-container">

        <h1>Forgot Password</h1>
        <p>Please enter your email to request an email to reset your password:</p>

        <div class="login-form-container">

            <form method="POST" action="{{ route('password.reset.store') }}">
                @csrf

                <div>
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Address">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div>
                    <button type="submit" class="btn btn-primary btn-tmobile">
                        {{ __('Request Email') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
