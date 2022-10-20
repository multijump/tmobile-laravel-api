@extends('layouts.app')

@section('content')
<div class="container registration-page-container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ $event->title }}</h1>
        </div>
    </div>

    <div class="row registration-form-container">
        <div class="col-md-12">
            <form id="register-form" novalidate="true" data-toggle="validator" method="POST" class="form">
                @csrf
                <div class="form-group text-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="language" data-translation-id="language">Preferred Language</label>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="language" id="language-english" value="English"
                                @if ($event->default_language == "English")
                                    checked
                                @endif
                                >
                                <label class="form-check-label" for="language-english" data-translation-id="language-english"> English</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="language" id="language-spanish" value="Spanish"
                                @if ($event->default_language == "Spanish")
                                    checked
                                @endif
                                >
                                <label class="form-check-label" for="language-spanish" data-translation-id="language-spanish"> Español</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="first-name" data-translation-id="first-name">First Name</label>
                        </div>
                        <div class="col-6">
                            <input id="first-name" style="text-transform: capitalize;" type="text"  class="form-control" name="first_name" value="" required data-error=" *Required Field">
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="last-name" data-translation-id="last-name">Last Name</label>
                        </div>
                        <div class="col-6">
                            <input id="last-name" type="text" style="text-transform: capitalize;"  class="form-control" name="last_name" value="" required data-error=" *Required Field">
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="email" data-translation-id="email">Email</label>
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
                            <label for="phone" data-translation-id="phone">Mobile Phone</label>
                        </div>
                        <div class="col-6">
                            <input id="phone" type="tel"  class="form-control" name="phone" value="" required data-error=" *Required Field">
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="zip-code" data-translation-id="zip-code">Zip Code</label>
                        </div>
                        <div class="col-6">
                            <input id="zip-code" type="text" class="form-control" name="zip_code" value="" required data-error=" *Required Field">
                        </div>
                    </div>
                </div>
                <div class="form-group input-row">
                    <div class="form-row">
                        <div class="col-3 text-right">
                            <label for="current_carrier" data-translation-id="current_carrier">Current Wireless Provider</label>
                        </div>
                        <div class="col-6">
                            <select id="current_carrier" name="current_carrier" class="form-control" required>
                                <option value="" data-translation-id="current_carrier_prompt" selected disabled>Please select...</option>
                                <option value="T-Mobile">T-Mobile</option>
                                <option value="Metro by T-Mobile">Metro by T-Mobile</option>
                                <option value="Verizon">Verizon</option>
                                <option value="AT&T">AT&T</option>
                                <option value="Boost">Boost</option>
                                <option value="Cricket">Cricket</option>
                                <option value="Straight Talk">Straight Talk</option>
                                <option value="Total Wireless">Total Wireless</option>
                                <option value="Other">Other</Other>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group text-row">
                    <div class="form-row">
                        <div class="col-4 offset-3">
                            <label for="is_contact" data-translation-id="is_contact">Stay connected with our latest news & offers.</label>
                        </div>
                        <div class="col-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_contact" id="contact-affirmative" value="1" required>
                                <label class="form-check-label" for="contact-affirmative" data-translation-id="contact-affirmative" required data-error=" *Required Field"> Sign Up</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_contact" id="contact-negative" value="0" required>
                                <label class="form-check-label" for="contact-negative" data-translation-id="contact-negative" required data-error=" *Required Field"> No Thanks</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-row">
                    <div class="form-row">
                        <div class="col-6 offset-3">
                            <label class="form-check-label" for="register-submit" data-translation-id="register-submit"> By registering you confirm you are 18 years or older and agree to our 
                                <a href="https://www.t-mobile.com/responsibility/privacy/privacy-policy"  target="_blank" rel="noopener noreferrer">Privacy Policy</a>.
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-buttons">
                                <button id="register-submit" data-submit="true" type="submit" class="btn btn-primary btn-tmobile" data-translation-id="complete">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 text-center">
            <p style="font-size: .6em" data-translation-id="contact-disclaimer">* If you select no, you will only be notified if you are selected as a winner. Note that not all events will contain a prize or contest.</p>
        </div>
        <!--<div class="col-12 text-center">
            <p style="font-size: .6em" data-translation-id="ccpa-dns">
                <a href="https://www.t-mobile.com/dns">Do Not Sell My Personal Information</a>
            </p>
        </div>-->
    </div>
</div>

<!-- Terms and conditions -->
{{--<div class="modal fade" id="terms-and-conditions-modal" tabindex="-1" role="dialog" aria-labelledby="termsAndConditionsModalLabel" aria-hidden="true">--}}
    {{--<div class="modal-dialog">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-body">--}}
                {{--<div class="text-center">--}}
                    {{--<p>To see how T-Mobile uses your information, please see our <a href="{{ env('PRIVACY_POLICY_LINK', 'https://www.t-mobile.com/company/website/privacypolicy.aspx') }}" target="_blank" rel="noopener noreferrer">privacy policy</a></p>--}}
                    {{--<button data-dismiss="modal" class="btn btn-primary btn-tmobile-modal" data-translation-id="ok">OK</button>--}}
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
                    <button data-dismiss="modal" class="btn btn-primary btn-tmobile-modal" data-translation-id="ok">OK</button>
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
                    <h3 style="color:#e20074" data-translation-id="thank-you">Thank You</h3>
                    <button data-dismiss="modal" class="btn btn-primary btn-tmobile-modal" data-translation-id="dismiss">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function clearInputs(){
    $('input[name=first_name]').val('');
    $('input[name=last_name]').val('');
    $('input[name=email]').val('');
    $('input[name=phone]').val('');
    $('input[name=zip_code]').val('');
    $('#current_carrier').val('');
    $('input[name=is_contact]').prop("checked", false);
    $('input[name=accept_terms]').prop("checked", false);
}

$(function() {

    var curLanguage = "{{ $event->default_language }}";
    if(curLanguage == "" || curLanguage == null || curLanguage == "null"){
        curLanguage = "English";
    }
    var translations = {
        "English": {
            "registration": "Registration",
            "fields-required": "* All Fields Are Required",
            "language": "Preferred Language",
            "is_18plus": "Are you over 18 years of age?",
            "is_18plus-affirmative": "Yes",
            "is_18plus-negative": "No",
            "is_18plus-fail-message": "Sorry, in order to participate in the drawing, you must be 18 years or older.",
            "first-name": "First Name",
            "last-name": "Last Name",
            "email": "Email",
            "phone": "Mobile Phone",
            "zip-code": "Zip Code",
            "is_customer": "Are you a T-Mobile customer?",
            "current_carrier": "Current Wireless Provider",
            "current_carrier_prompt": "Please select...",
            "customer-affirmative": "Yes",
            "customer-negative": "No",
            "is_contact": "Stay connected with our latest news & offers.",
            "contact-affirmative": "Sign Up",
            "contact-negative": "No Thanks",
            "register-submit": 'By registering you confirm you are 18 years or older and agree to our <a href="https://www.t-mobile.com/responsibility/privacy/privacy-policy"  target="_blank" rel="noopener noreferrer">Privacy Policy</a>.',
            "complete": "Complete",
            "contact-disclaimer": "",
            "registration-duplicate": "You have already registered,<br>so you’re all set!<br>",
            "thank-you": "Thank You",
            "ok": "OK",
            "dismiss": "Dismiss",
            "ccpa-dns": '<a href="https://www.t-mobile.com/dns">Do Not Sell My Personal Information</a>'
        },
        "Spanish": {
            "registration": "Inscripción",
            "fields-required": "* Todos los campos son obligatorios",
            "language": "Idioma de preferencia",
            "is_18plus": "¿Eres mayor de 18 años de edad?",
            "is_18plus-affirmative": "Sí",
            "is_18plus-negative": "No",
            "is_18plus-fail-message": "Lo sentimos. Para participar en el sorteo debes tener al menos 18 años de edad.",
            "first-name": "Nombre",
            "last-name": "Apellido",
            "email": "Email",
            "phone": "Teléfono celular",
            "zip-code": "Código postal",
            "is_customer": "¿Eres cliente de T-Mobile?",
            "current_carrier": "Compañía actual de servicio móvil",
            "current_carrier_prompt": "Selecciona una…",
            "customer-affirmative": "Sí",
            "customer-negative": "No",
            "is_contact": "Mantente informado de nuestras noticias y ofertas más recientes.",
            "contact-affirmative": "Inscríbanme",
            "contact-negative": "No, gracias",
            "register-submit": 'Al inscribirte, confirmas que tienes al menos 18 años de edad y que aceptas nuestra <a href="https://es.t-mobile.com/responsibility/privacy/privacy-policy"  target="_blank" rel="noopener noreferrer">Política de Privacidad</a>.',
            "complete": "Enviar",
            "contact-disclaimer": "",
            "registration-duplicate": "Ya estás inscrito,<br>no es necesario hacer nada más.<br>",
            "thank-you": "Gracias",
            "ok": "Aceptar",
            "dismiss": "Descartar",
            "ccpa-dns": '<a href="https://es.t-mobile.com/dns">No vender mi información personal</a>'
        },

    }

    var registerForm = $("#register-form");

    var submitButton = $("#register-submit");

    var clearButton = $("#register-clear");


    submitButton.on('click', function(event) {
        event.preventDefault();
        // Submit Form
        if (registerForm[0].checkValidity() === false || (!$('#contact-affirmative').prop("checked") && !$('#contact-negative').prop("checked"))) {
            event.stopPropagation();
        } else {
            //do your ajax submission here
            submitForm();
        }
        registerForm.addClass('was-validated');
    });

    $('#language-english').click(function() {
        changeLanguage("English");
    });

    $('#language-spanish').click(function() {
        changeLanguage("Spanish");
    });

    $('#language-other').click(function() {
        changeLanguage("English");
    });

    clearButton.on('click', function(event) {
        event.preventDefault();

        registerForm.removeClass('was-validated');

        // Clear inputs
        clearInputs();
    });

    function changeLanguage(option){
        curLanguage = option;
        var labels = document.querySelectorAll("[data-translation-id]");
        for(var i = 0; i < labels.length; i++){
            var curLabel = labels[i];
            var labelFor = curLabel.getAttribute("data-translation-id");
            if(translations[option][labelFor] != null){
                curLabel.innerHTML = translations[option][labelFor];
            }
        }
    }

    changeLanguage(curLanguage);

    function submitForm() {

        var firstNameInput = $('input[name=first_name]');
        var lastNameInput = $('input[name=last_name]');
        var emailInput = $('input[name=email]');
        var phoneInput = $('input[name=phone]');
        var zipCodeInput = $('input[name=zip_code]');
        var languageInput = $('input[name=language]:checked');
        var currentCarrierInput = $('#current_carrier');
        var isContactInput = $('#contact-affirmative');
        var acceptTermsInput = $('input[name=accept_terms]');
        var api_token = null;
        @auth
            api_token = "{!! \Auth::user()->api_token !!}";
        @endauth

        var ajaxData = {
                contentType: 'application/json',
                first_name: firstNameInput.val(),
                last_name: lastNameInput.val(),
                email: emailInput.val(),
                // remove mask on phone input
                phone: phoneInput.cleanVal(),
                zip_code: zipCodeInput.val(),
                language: languageInput.val(),
                current_carrier: currentCarrierInput.val(),
                is_customer: currentCarrierInput.val() == "T-Mobile" || currentCarrierInput.val() == "Metro by T-Mobile" ? 1 : 0,
                is_contact: isContactInput.prop("checked") ? 1 : 0,
                accept_terms: 1,
                event_id: "{!! $event->id !!}"
            };
        if(api_token != null){
            ajaxData.api_token = api_token;
        }
        $.ajax({
            type: "POST",
            url: "{!! route('events.participants.register.store', $event->id) !!}",
            data: ajaxData,
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
                    clearInputs();
                    registerForm.removeClass('was-validated');

                    // Display thank you modal.
                    $('#thank-you-modal').modal('show');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // Display error response modal with message.
                var errorMessage = XMLHttpRequest.responseJSON.message;
                if(XMLHttpRequest.responseJSON.code == "23000"){
                    errorMessage = translations[curLanguage]["registration-duplicate"];
                    clearInputs();
                    registerForm.removeClass('was-validated');
                }
                document.getElementById("error-response-text-container").innerHTML = errorMessage;
                $('#error-response-modal').modal('show');
            }
        });
    }

    $('#phone').mask('000-000-0000', {placeholder: "   -  -  "});
    $('#zip-code').mask('00000');
});

</script>
@endsection
