@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 intro-text-list">
            <h1>Survey</h1>
        </div>
    </div>

    <div class="create-event-form-container survey-form">
        <form id="survey-form" novalidate="true" data-toggle="validator" method="POST" class="form">
            @csrf

            <div id="age-check" class="form-group age-check">
                <p class="survey-question">Are you over 18 years of age?</p>
                <div class="survey-answer-wrapper">
                    <div>
                        <input type="radio" id="age-1" name="age" value="18+" required
                            @if (old('age') == '18+')
                                checked
                            @endif
                        >
                        <label for="age-1">Yes</label>
                    </div>
                    <div>
                        <input type="radio" id="age-0" name="age" value="-18" required
                            @if (old('age') == '-18')
                                checked
                            @endif
                        >
                        <label for="age-0">No</label>
                    </div>
                </div>
            </div>
            <div id="length-with-provider-group" class="form-group">
                <p class="survey-question">About how long have you been with your current wireless service provider?</p>
                <div class="survey-answer-wrapper">
                    <div>
                        <input type="radio" id="length-1" name="length_with_provider" value="Less than 1 month" required
                            @if (old('length_with_provider') == 'Less than 1 month')
                                checked
                            @endif
                        >
                        <label for="length-1">Less than 1 month</label>
                    </div>
                    <div>
                        <input type="radio" id="length-2" name="length_with_provider" value="1 month to under 3 months" required
                            @if (old('length_with_provider') == '1 month to under 3 months')
                                checked
                            @endif
                        >
                        <label for="length-2">1 month to under 3 months</label>
                    </div>
                    <div>
                        <input type="radio" id="length-3" name="length_with_provider" value="3 months to under 6 months" required
                            @if (old('length_with_provider') == '3 months to under 6 months')
                                checked
                            @endif
                        >
                        <label for="length-3">3 months to under 6 months</label>
                    </div>
                    <div>
                        <input type="radio" id="length-4" name="length_with_provider" value="6 months to under 1 year" required
                            @if (old('length_with_provider') == '6 months to under 1 year')
                                checked
                            @endif
                        >
                        <label for="length-4">6 months to under 1 year</label>
                    </div>
                    <div>
                        <input type="radio" id="length-5" name="length_with_provider" value="1 year to under 2 years" required
                            @if (old('length_with_provider') == '1 year to under 2 years')
                                checked
                            @endif
                        >
                        <label for="length-5">1 year to under 2 years</label>
                    </div>
                    <div>
                        <input type="radio" id="length-6" name="length_with_provider" value="2 years to under 4 years" required
                            @if (old('length_with_provider') == '2 years to under 4 years')
                                checked
                            @endif
                        >
                        <label for="length-6">2 years to under 4 years</label>
                    </div>
                    <div>
                        <input type="radio" id="length-7" name="length_with_provider" value="4 years to under 8 years" required
                            @if (old('length_with_provider') == '4 years to under 8 years')
                                checked
                            @endif
                        >
                        <label for="length-7">4 years to under 8 years</label>
                    </div>
                    <div>
                        <input type="radio" id="length-8" name="length_with_provider" value="8 or more years" required
                            @if (old('length_with_provider') == '8 or more years')
                                checked
                            @endif
                        >
                        <label for="length-8">8 or more years</label>
                    </div>
                    <div>
                        <input type="radio" id="length-9" name="length_with_provider" value="Don't know" required
                            @if (old('length_with_provider') == 'Don\'t know')
                                checked
                            @endif
                        >
                        <label for="length-9">Don't know</label>
                    </div>
                </div>
            </div>
            <div id="leave-likelihood-group" class="form-group">
                <p class="survey-question">What is the likelihood that you will leave your carrier for another wireless service provider in the next year?</p>
                <div class="survey-answer-wrapper">
                    <div>
                        <input type="radio" id="leave-0" name="leave_likelihood" value="I definitely will not" required
                            @if (old('leave_likelihood') == 'I definitely will not')
                                checked
                            @endif
                        >
                        <label for="leave-0">I definitely will not</label>
                    </div>
                    <div>
                        <input type="radio" id="leave-1" name="leave_likelihood" value="I probably will not" required
                            @if (old('leave_likelihood') == 'I probably will not')
                                checked
                            @endif
                        >
                        <label for="leave-1">I probably will not</label>
                    </div>
                    <div>
                        <input type="radio" id="leave-2" name="leave_likelihood" value="I might or might not" required
                            @if (old('leave_likelihood') == 'I might or might not')
                                checked
                            @endif
                        >
                        <label for="leave-2">I might or might not</label>
                    </div>
                    <div>
                        <input type="radio" id="leave-3" name="leave_likelihood" value="I probably will" required
                            @if (old('leave_likelihood') == 'I probably will')
                                checked
                            @endif
                        >
                        <label for="leave-3">I probably will</label>
                    </div>
                    <div>
                        <input type="radio" id="leave-4" name="leave_likelihood" value="I definitely will" required
                            @if (old('leave_likelihood') == 'I definitely will')
                                checked
                            @endif
                        >
                        <label for="leave-4">I definitely will</label>
                    </div>
                </div>
            </div>
            <div id="most-important-group" class="form-group">
                <p class="survey-question">What is most important to you when choosing a wireless service provider?</p>
                <div class="survey-answer-wrapper">
                    <div>
                        <input type="radio" id="important-1" name="most_important" value="Network coverage and reliability" required
                            @if (old('most_important') == 'Network coverage and reliability')
                                checked
                            @endif
                        >
                        <label for="important-1">Network coverage and reliability</label>
                    </div>
                    <div>
                        <input type="radio" id="important-2" name="most_important" value="A network that offers 5G" required
                            @if (old('most_important') == 'A network that offers 5G')
                                checked
                            @endif
                        >
                        <label for="important-2">A network that offers 5G</label>
                    </div>
                    <div>
                        <input type="radio" id="important-3" name="most_important" value="Not being locked into a contract" required
                            @if (old('most_important') == 'Not being locked into a contract')
                                checked
                            @endif
                        >
                        <label for="important-3">Not being locked into a contract</label>
                    </div>
                    <div>
                        <input type="radio" id="important-4" name="most_important" value="They carry the right phone for me" required
                            @if (old('most_important') == 'They carry the right phone for me')
                                checked
                            @endif
                        >
                        <label for="important-4">They carry the right phone for me</label>
                    </div>
                    <div>
                        <input type="radio" id="important-5" name="most_important" value="They offer the best price" required
                            @if (old('most_important') == 'They offer the best price')
                                checked
                            @endif
                        >
                        <label for="important-5">They offer the best price</label>
                    </div>
                </div>
            </div>
            <div id="brand-for-you" class="form-group">
                <p class="survey-question">Do you feel like T-Mobile is a brand for you?</p>
                <div class="survey-answer-wrapper">
                    <div>
                        <input type="radio" id="rating-1" name="brand_rating" value="Yes, strongly agree" required
                            @if (old('brand_rating') == 'Yes, strongly agree')
                                checked
                            @endif
                        >
                        <label for="rating-1">Yes, strongly agree</label>
                    </div>
                    <div>
                        <input type="radio" id="rating-2" name="brand_rating" value="Yes, agree" required
                            @if (old('brand_rating') == 'Yes, agree')
                                checked
                            @endif
                        >
                        <label for="rating-2">Yes, agree</label>
                    </div>
                    <div>
                        <input type="radio" id="rating-3" name="brand_rating" value="Maybe" required
                            @if (old('brand_rating') == 'Maybe')
                                checked
                            @endif
                        >
                        <label for="rating-3">Maybe</label>
                    </div>
                    <div>
                        <input type="radio" id="rating-4" name="brand_rating" value="No, probably not" required
                            @if (old('brand_rating') == 'No, probably not')
                                checked
                            @endif
                        >
                        <label for="rating-4">No, probably not</label>
                    </div>
                    <div>
                        <input type="radio" id="rating-5" name="brand_rating" value="No, definitely not" required
                            @if (old('brand_rating') == 'No, definitely not')
                                checked
                            @endif
                        >
                        <label for="rating-5">No, definitely not</label>
                    </div>
                </div>
            </div>
            <div class="form-group" id="brand-rating-why-group" style="display: none">
                <p class="survey-question" id="brand-rating-why-question">Why don't you feel that T-Mobile is a brand for you?</p>
                <textarea name="brand_rating_why" id="brand_rating_why" style="width: 100%; height: 150px;">{{ old('brand_rating_why') }}</textarea>
            </div>
            <div id="connect-wherever-group" class="form-group">
                <p class="survey-question">Do you feel like T-Mobile would give/gives you the ability to connect wherever you are?</p>
                <div class="survey-answer-wrapper">
                    <div>
                        <input type="radio" id="connect-0" name="connect_wherever" value="Yes" required
                            @if (old('connect_wherever') == 'Yes')
                                checked
                            @endif
                        >
                        <label for="connect-0">Yes</label>
                    </div>
                    <div>
                        <input type="radio" id="connect-1" name="connect_wherever" value="No" required
                            @if (old('connect_wherever') == 'No')
                                checked
                            @endif
                        >
                        <label for="connect-1">No</label>
                    </div>
                </div>
            </div>
            <div class="form-group" id="connect-wherever-why-group" style="display: none">
                <p class="survey-question">Why don't you feel that T-Mobile would give/gives you the ability to connect wherever you are?</p>
                <textarea name="connect_wherever_why" id="connect_wherever_why" style="width: 100%; height: 150px;">{{ old('connect_wherever_why') }}</textarea>
            </div>
            <div id="consider-switching" class="form-group">
                <p class="survey-question">Would you ever consider switching to T-Mobile?</p>
                <div class="survey-answer-wrapper">
                    <div>
                        <input type="radio" id="switch-0" name="switch_rating" value="Yes" required
                            @if (old('switch_rating') == 'Yes')
                                checked
                            @endif
                        >
                        <label for="switch-0">Yes</label>
                    </div>
                    <div>
                        <input type="radio" id="switch-1" name="switch_rating" value="No" required
                            @if (old('switch_rating') == 'No')
                                checked
                            @endif
                        >
                        <label for="switch-1">No</label>
                    </div>
                    <div>
                        <input type="radio" id="switch-2" name="switch_rating" value="I am already a T-Mobile Customer" required
                            @if (old('switch_rating') == 'I\'m already on T-Mobile!')
                                checked
                            @endif
                        >
                        <label for="switch-2">I am already a T-Mobile Customer</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <p class="survey-question">What could T-Mobile do to enhance your wireless experience?</p>
                <textarea name="comments" id="comments" style="width: 100%; height: 150px;">{{ old('comments') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-tmobile">Submit</button>
            <!--<br>
            <br>
            <p style="font-size: .6em">
                <a href="https://www.t-mobile.com/dns">Do Not Sell My Personal Information</a>
            </p>-->
        </div>
        </form>

    </div>
</div>

<!-- Form submission success -->
<div class="modal fade" id="thank-you-modal" tabindex="-1" role="dialog" aria-labelledby="thankYouModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <h3 style="color:#e20074">Thank You!</h3>
                    <p id="modal-18-text" class="hidden">Sorry, in order to participate in the survey, you must be 18 years or older.</p>
                    <button data-dismiss="modal" class="btn btn-primary btn-tmobile-modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function clearInputs() {
        $('input[type=radio]').prop('checked', false);
        $('#comments').val('');
        setBrandWhyNotVisibility(false);
        $('#brand_rating_why').val('');
        $('#connect_wherever_why').val('');
        setConnectWhereverWhyNotVisibility(false);
    }

        function setBrandWhyNotVisibility(shouldDisplay){
            if(shouldDisplay){
                document.getElementById('brand-rating-why-group').style.display = "block";
            }else{
                document.getElementById('brand-rating-why-group').style.display = "none";
            }
        }

        function setConnectWhereverWhyNotVisibility(shouldDisplay){
            if(shouldDisplay){
                document.getElementById('connect-wherever-why-group').style.display = "block";
            }else{
                document.getElementById('connect-wherever-why-group').style.display = "none";
            }
        }

    $(document).ready(function() {
        $('.age-check').click(function() {
            if ($('#age-0').prop('checked')) {
                clearInputs();
                $('#modal-18-text').removeClass('hidden');
                $('#thank-you-modal').modal('show');
                window.scrollTo(0, 0);
            }
        })

        $('#rating-1').click(function(){setBrandWhyNotVisibility(false);});
        $('#rating-2').click(function(){setBrandWhyNotVisibility(false);});
        $('#rating-3').click(function(){setBrandWhyNotVisibility(true); document.getElementById('brand-rating-why-question').innerHTML = "Why do you feel that T-Mobile might be a brand for you?";});
        $('#rating-4').click(function(){setBrandWhyNotVisibility(true); document.getElementById('brand-rating-why-question').innerHTML = "Why don't you feel that T-Mobile is a brand for you?";});
        $('#rating-5').click(function(){setBrandWhyNotVisibility(true); document.getElementById('brand-rating-why-question').innerHTML = "Why don't you feel that T-Mobile is a brand for you?";});
        $('#connect-0').click(function(){setConnectWhereverWhyNotVisibility(false);});
        $('#connect-1').click(function(){setConnectWhereverWhyNotVisibility(true);});

        $('[type="submit"').click(function() {
            event.preventDefault();

            if ($('[type="submit"').hasClass('disabled')) {
                if ($('input[name=age]:checked').val() === undefined) {
                    $('html, body').animate({
                        scrollTop: ($('#age-check').offset().top)
                    },500);
                } else if ($('input[name=length_with_provider]:checked').val() === undefined) {
                    $('html, body').animate({
                        scrollTop: ($('#length-with-provider-group').offset().top)
                    },500);
                } else if ($('input[name=leave_likelihood]:checked').val() === undefined) {
                    $('html, body').animate({
                        scrollTop: ($('#leave-likelihood-group').offset().top)
                    },500);
                } else if ($('input[name=brand_rating]:checked').val() === undefined) {
                    $('html, body').animate({
                        scrollTop: ($('#brand-for-you').offset().top)
                    },500);
                } else if ($('input[name=connect_wherever]:checked').val() === undefined) {
                    $('html, body').animate({
                        scrollTop: ($('#connect-wherever-group').offset().top)
                    },500);
                } else if ($('input[name=switch_rating]:checked').val() === undefined) {
                    $('html, body').animate({
                        scrollTop: ($('#consider-switching').offset().top)
                    },500);
                }

                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{!! route('surveys.store', ['event_id' => $event->id]) !!}",
                data: {
                    contentType: 'application/json',
                    age: $('input[name=age]:checked').val(),
                    length_with_provider: $('input[name=length_with_provider]:checked').val(),
                    leave_likelihood: $('input[name=leave_likelihood]:checked').val(),
                    most_important: $('input[name=most_important]:checked').val(),
                    brand_rating: $('input[name=brand_rating]:checked').val(),
                    brand_rating_why: $('[name=brand_rating_why]').val(),
                    connect_wherever: $('input[name=connect_wherever]:checked').val(),
                    connect_wherever_why: $('[name=connect_wherever_why]').val(),                    
                    switch_rating: $('input[name=switch_rating]:checked').val(),
                    comments: $('[name=comments]').val(),
                },
                success: function (data) {
                    $('#modal-18-text').addClass('hidden');
                    $('#thank-you-modal').modal('show');
                    window.scrollTo(0, 0);
                    clearInputs();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    // console.log('error', XMLHttpRequest.responseJSON.message, textStatus, errorThrown)
                }
            });
        });
    })

    @if (session('status') == 'Thank You!')
        $('#thank-you-modal').modal('show');
    @endif

    if("{{ $event->company }}" == "Metro"){
        $('#nav-logo').attr("src","/jpg/metro.jpg");
        $('#nav-logo').attr("alt","Metro");
    }

</script>
@endsection
