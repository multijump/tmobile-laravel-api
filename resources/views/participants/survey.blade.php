@extends('layouts.app')

@section('content')
<div class="container registration-page-container">
    <div class="row">
        <div class="col-md-12">
            <h1>Survey</h1>
            <h2>{{ $event->title }} &ndash; #{{ $event->store_number }}&nbsp; {{ $event->start_date->format('m/d/Y') }}</h2>
        </div>
    </div>

    <div class="row registration-form-container">
        <div class="col-md-12 text-center">
            <p style="font-size: .6em">* All Fields Are Required</p>
        </div>
        <div class="col-md-12">
            <form id="register-form" novalidate="true" data-toggle="validator" action="{{ route('events.participants.register.store', $event->id) }}" method="POST" class="form">
                @csrf
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="first-name">First Name</label>
                        </div>
                        <div class="col-6">
                            <input id="first-name" style="text-transform: capitalize;" type="text"  class="form-control" name="first_name" value="" required data-error=" *Required Field">
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="last-name">Last Name</label>
                        </div>
                        <div class="col-6">
                            <input id="last-name" type="text" style="text-transform: capitalize;"  class="form-control" name="last_name" value="" required data-error=" *Required Field">
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="email">Email</label>
                        </div>
                        <div class="col-6">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                            <input id="email" class="form-control" type="email"  name="email" value="" required>
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="phone">Mobile Phone</label>
                        </div>
                        <div class="col-6">
                            <input id="phone" type="tel"  class="form-control" name="phone" value="" required data-error=" *Required Field">
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="zip-code">Zip Code</label>
                        </div>
                        <div class="col-6">
                            <input id="zip-code" type="text" class="form-control" name="zip_code" value="" required data-error=" *Required Field">
                        </div>
                    </div>
                </div>
                <div class="form-group text-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="language">Preferred Language</label>
                        </div>
                        <div class="col-6">
                        <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="language" id="language-english" value="English" checked>
                                <label class="form-check-label" for="language-english"> English</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="language" id="language-spanish" value="Spanish">
                                <label class="form-check-label" for="language-spanish"> Spanish</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="language" id="language-other" value="Other">
                                <label class="form-check-label" for="language-other"> Other</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-row">
                    <div class="form-row">
                        <div class="col-4 offset-3">
                            <label >Are you a T-Mobile customer?</label>
                        </div>
                        <div class="col-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_customer" id="customer-affirmative" value="1">
                                <label class="form-check-label" for="customer-affirmative"> Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_customer" id="customer-negative" value="0">
                                <label class="form-check-label" for="customer-negative"> No</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-row">
                    <div class="form-row">
                        <div class="col-4 offset-3">
                            <label >** Stay connected with our latest news & offers.</label>
                        </div>
                        <div class="col-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_contact" id="contact-affirmative" value="1" checked>
                                <label class="form-check-label" for="contact-affirmative"> Sign Up</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_contact" id="contact-negative" value="0">
                                <label class="form-check-label" for="contact-negative"> No Thanks</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-row">
                    <div class="form-row">
                        <div class="col-6 offset-3">
                                <label class="form-check-label"> By registering you confirm you are 18 years or older and agree to our
                                    <a href="https://www.t-mobile.com/responsibility/privacy/privacy-policy"  target="_blank" rel="noopener noreferrer">Privacy Policy</a>.
                                </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-buttons">
                                <button id="register-submit" data-submit="true" type="submit" class="btn btn-primary btn-tmobile">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 text-center">
            <p style="font-size: .6em">** If you select no, you will only be notified, via email, if you are selected as a winner. Note that not all events will contain a prize or contest.</p>
        </div>
    </div>
</div>

<!-- Terms and conditions -->
{{--<div class="modal fade" id="terms-and-conditions-modal" tabindex="-1" role="dialog" aria-labelledby="termsAndConditionsModalLabel" aria-hidden="true">--}}
    {{--<div class="modal-dialog">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-body">--}}
                {{--<div class="text-center">--}}
                    {{--<p>To see how T-Mobile uses your information, please see our <a href="{{ env('PRIVACY_POLICY_LINK', 'https://www.t-mobile.com/company/website/privacypolicy.aspx') }}" target="_blank" rel="noopener noreferrer">privacy policy</a></p>--}}
                    {{--<button data-dismiss="modal" class="btn btn-primary btn-tmobile-modal">Ok</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}

<!-- Form submission error -->
<div class="modal fade" id="error-response-modal" tabindex="-1" role="dialog" aria-labelledby="errorResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <span id="error-response-text-container" style="color:#e20074"></span>
                    <button data-dismiss="modal" class="btn btn-primary btn-tmobile-modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form submission success -->
<div class="modal fade" id="thank-you-modal" tabindex="-1" role="dialog" aria-labelledby="thankYouModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <h3 style="color:#e20074">Thank You</h3>
                    <button data-dismiss="modal" class="btn btn-primary btn-tmobile-modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

$(function() {

    var registerForm = $("#register-form");

    var submitButton = $("#register-submit");

    var clearButton = $("#register-clear");

    submitButton.on('click', function(event) {
        event.preventDefault();
        // Submit Form
        if (registerForm[0].checkValidity() === false) {
            event.stopPropagation();
        } else {
            //do your ajax submission here
            submitForm();
        }
        registerForm.addClass('was-validated');
    });

    clearButton.on('click', function(event) {
        event.preventDefault();

        registerForm.removeClass('was-validated');

        // Clear inputs
        $('input[name=first_name]').val('');
        $('input[name=last_name]').val('');
        $('input[name=email]').val('');
        $('input[name=phone]').val('');
        $('#customer-affirmative').prop("checked", true);
        $('#contact-affirmative').prop("checked", true);
        $('input[name=accept_terms]').prop("checked", false);
    });


    function submitForm() {

        var firstNameInput = $('input[name=first_name]');
        var lastNameInput = $('input[name=last_name]');
        var emailInput = $('input[name=email]');
        var phoneInput = $('input[name=phone]');
        var languageInput = $('input[name=language]:checked');
        var isCustomerInput = $('#customer-affirmative');
        var isContactInput = $('#contact-affirmative');
        var acceptTermsInput = $('input[name=accept_terms]');

        $.ajax({
            type: "POST",
            url: "{!! route('events.participants.register.store', $event->id) !!}",
            data: {
                contentType: 'application/json',
                first_name: firstNameInput.val(),
                last_name: lastNameInput.val(),
                email: emailInput.val(),
                // remove mask on phone input
                phone: phoneInput.cleanVal(),
                language: languageInput.val(),
                is_customer: isCustomerInput.prop("checked") ? 1 : 0,
                is_contact: isContactInput.prop("checked") ? 1 : 0,
                accept_terms: 1,
                event_id: "{!! $event->id !!}",
                api_token: "{!! \Auth::user()->api_token !!}"
            },
            success: function (data) {

                if (data.hasOwnProperty('errors')) {
                    // Error
                    jQuery.each(data.errors, function(key, value){
                        var errorTextContainer = $('#error-response-text-container');
                        errorTextContainer.empty();
                        errorTextContainer.append('<p>'+value+'</p>');
                        $('#error-response-modal').modal('show');
                    });

                } else {
                    // Success

                    // Clear inputs
                    firstNameInput.val('');
                    lastNameInput.val('');
                    emailInput.val('');
                    phoneInput.val('');
                    $('#language-english').prop("checked", true);
                    $('#customer-affirmative').prop("checked", true);
                    $('#contact-affirmative').prop("checked", true);
                    $('input[name=accept_terms]').prop("checked", false);
                    registerForm.removeClass('was-validated');

                    // Display thank you modal.
                    $('#thank-you-modal').modal('show');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // Display error response modal with message.
                var errorMessage = XMLHttpRequest.responseJSON.message;
                document.getElementById("error-response-text").innerHTML = errorMessage;
                $('#error-response-modal').modal('show');
            }
        });
    }

    $('#phone').mask('000-000-0000', {placeholder: "   -  -  "});
});

</script>
@endsection
