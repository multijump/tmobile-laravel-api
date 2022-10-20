@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h1>Edit Profile</h1>
                </div>
                <form novalidate="true" data-toggle="validator" action="{{ route('users.update', $user->id) }}" method="POST" class="form">
                    @csrf
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="first-name">First</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="first-name"  class="form-control" name="first_name" value="{{ old('first_name') ?: $user->first_name }}" required data-error="**Required Field">
                            </div>
                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="last-name">Last</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="last-name"  class="form-control" name="last_name" value="{{ old('last_name') ?: $user->last_name }}" required data-error="**Required Field">
                            </div>
                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-6">
                                <input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email"  name="email" value="{{ old('email') ?: $user->email }}" required >
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="password" >{{ __('Password') }}</label>
                            </div>
                            <div class="col-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
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
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="password-confirm" >{{ __('Confirm Password') }}</label>
                            </div>
                            <div class="col-6">
                                @if ($errors->has('password-confirm'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password-confirm') }}</strong>
                                    </span>
                                @endif
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                <div class="dynamic-password-check info-block">
                                    <div><span id="password-same-validate">❌</span><span>Passwords match</span></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-12">
                                <span class="text-center">
                                <div class="form-buttons">
                                    <button data-submit="true" type="submit" class="btn btn-primary btn-tmobile">Submit</button>
                                    <a href="@if(!empty(\Auth::user()) && \Auth::user()->hasAdminRole()){{ route('admin.home') }}@else{{ route('home') }}@endif" class="btn btn-primary btn-tmobile">Cancel</a>
                                </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
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
