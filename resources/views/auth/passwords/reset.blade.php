@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

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
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                <div class="dynamic-password-check info-block" style="text-align: left">
                                    <div><span id="password-same-validate">❌</span><span>Passwords match</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
