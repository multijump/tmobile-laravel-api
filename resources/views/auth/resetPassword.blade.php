@extends('layouts.app')

@section('content')
    <div class="guest-container">

        <h1>Reset Password</h1>
        <p>Please enter a new password. Password must be 8 characters long and contain the following:</p>

        <div class="row">
            <div class="col-md-12 intro-text-list">
                <ul>
                    <li>One uppercase character (A-Z)</li>
                    <li>One lowercase character (a-z)</li>
                    <li>One number (0-9)</li>
                    <li>A special character (~!@#$%^&*_-+=`|\(){}[]:;"'<>,.?/)</li>
                </ul>
            </div>
        </div>

        <div class="login-form-container">

            <form method="POST" action="{{ route('password.set.store', $user->unique_key) }}">
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
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                    <div class="dynamic-password-check info-block">
                        <div><span id="password-uppercase-validate">❌</span><span>One uppercase character (A-Z)</span></div>
                        <div><span id="password-lowercase-validate">❌</span><span>One lowercase character (a-z)</span></div>
                        <div><span id="password-number-validate">❌</span><span>One number (0-9)</span></div>
                        <div><span id="password-special-validate">❌</span><span>A special character (~!@#$%^&*_-+=`|\(){}[]:;"'<>,.?/)</span></div>
                    </div>
                </div>

                <div>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password">
                    <div class="dynamic-password-check info-block">
                        <div><span id="password-same-validate">❌</span><span>Passwords match</span></div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary btn-tmobile">
                        {{ __('Set Password') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

<script>

$(function() {
    $("#password").on('input', validate);
    $("#password-confirm").on('input', validate);
    function validate(){
        let newVal = $("#password").val();
        let confirmVal = $("#password-confirm").val();
        if(/[A-Z]/g.test(newVal)){
            $("#password-uppercase-validate").text("✅");
        }else{
            $("#password-uppercase-validate").text("❌");
        }
        if(/[a-z]/g.test(newVal)){
            $("#password-lowercase-validate").text("✅");
        }else{
            $("#password-lowercase-validate").text("❌");
        }
        if(/\d/g.test(newVal)){
            $("#password-number-validate").text("✅");
        }else{
            $("#password-number-validate").text("❌");
        }
        if(/[~!@#$%^&*_\-+=`|\\(){}\[\]:;"'<>,.?\/]/g.test(newVal)){
            $("#password-special-validate").text("✅");
        }else{
            $("#password-special-validate").text("❌");
        }
        if(confirmVal === "" || confirmVal !== newVal){
            $("#password-same-validate").text("❌");
        }else{
            $("#password-same-validate").text("✅");
        }
    }
    validate();
});

</script>
@endsection
