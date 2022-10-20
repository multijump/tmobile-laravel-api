@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 intro-text-list">
            <h1>Create an Event</h1>
        </div>
    </div>

    <div class="create-event-form-container">
        <form novalidate="true" data-toggle="validator" action="{{ route('events.store') }}" method="POST" class="form">
            @csrf
            <div class="form-group">
                <label for="title">Event Name <span class="help-block" style="font-size: .8em;">(Customers will see the event name — so please make sure the spelling and capitalization are correct. Ex: Ariana Grande Ticket vs. AG ticket giveaway)</span><div class="help-block with-errors"></div></label>
                <input type="text"  class="form-control" name="title" value="{{ old('title') }}" required data-error=" **Required Field">
            </div>
            <div class="form-group">
                <label for="store_number">Store Number <div class="help-block with-errors"></div></label>
                <input type="text"  class="form-control" name="store_number" value="{{ old('workfront_number') }}" maxlength="4" required data-error=" **Required Field">
            </div>
            <div class="form-group">
                <label for="">Workfront ID<div class="help-block with-errors"></div></label>
                <input type="text"  class="form-control" name="workfront_id" value="{{ old('workfront_id') }}" maxlength="255">
            </div>
            <div class="form-group">
                <label for="start_date">Event Start Date <div class="help-block with-errors"> </div></label>
                <input class="form-control date" name="start_date" value="{{ old('start_date') }}" required data-error=" **Required Field" placeholder="MM/DD/YYYY">
            </div>
            <div class="form-group">
                <label for="end_date">Event End Date <div class="help-block with-errors"></div></label>
                <input class="form-control date" name="end_date" value="{{ old('end_date') }}" required data-error=" **Required Field" placeholder="MM/DD/YYYY">
                <br>
                <span class="help-block" style="font-size: .8em;">Events can be a maximum of 3 days long. Please create multiple events if your event is longer.</span>
            </div>
            <div class="form-group">
                <label for="description">Event Description <span class="help-block" style="font-size: .8em;">(max. 500 characters)</span><div class="help-block with-errors"></div></label>
                <textarea  class="form-control" name="description" maxlength="500" value="{{ old('description') }}" rows="3" required data-error=" **Required Field"></textarea>
            </div>
            <div class="form-group">
                <label for="region">Region<div class="help-block with-errors"></div></label>
                <select id="region" class="form-control" name="region">
                    <option value="Central">Central</option>
                    <option value="East">East</option>
                    <option value="South">South</option>
                    <option value="West">West</option>
                </select>
            </div>
            <div class="form-group">
                <label for="state">US State<div class="help-block with-errors"></div></label>
                <select id="state" class="form-control" name="state">
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select>
            </div>
            <div class="form-group">
                <div>Select event type</div>
                <div>
                    <span>
                        <input
                            type="radio"
                            name="company"
                            value="T-Mobile"
                            required
                            @if (old('company') === "T-Mobile")
                                checked
                            @endif
                        >
                        <label for="company">T-Mobile</label>
                    </span>

                    <span style="margin-left: 15px;">
                        <input
                            type="radio"
                            name="company"
                            value="Metro"
                            required
                            @if (old('company') === "Metro")
                                checked
                            @endif
                        >
                        <label for="company">Metro by T-Mobile</label>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div>Select the primary language for your event</div>
                <div>
                    <span>
                        <input
                            type="radio"
                            name="default_language"
                            value="English"
                            required
                            @if (old('default_language') === "English")
                                checked
                            @endif
                        >
                        <label for="default_language">English</label>
                    </span>

                    <span style="margin-left: 15px;">
                        <input
                            type="radio"
                            name="default_language"
                            value="Spanish"
                            required
                            @if (old('default_language') === "Spanish")
                                checked
                            @endif
                        >
                        <label for="default_language">Spanish</label>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div>Exit survey will be used on-site</div>
                <div>
                    <span>
                        <input
                            type="radio"
                            name="has_surveys_enabled"
                            value="1"
                            required
                            @if (old('has_surveys_enabled') === 1)
                                checked
                            @endif
                        >
                        <label for="has_surveys_enabled">Yes</label>
                    </span>

                    <span style="margin-left: 15px;">
                        <input
                            type="radio"
                            name="has_surveys_enabled"
                            value="0"
                            required
                            @if (old('has_surveys_enabled') === 0)
                                checked
                            @endif
                        >
                        <label for="has_surveys_enabled">No</label>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div>Does your event require hands free registration via QR code?</div>
                <div>
                    <span>
                        <input
                            type="radio"
                            name="public"
                            value="1"
                            required
                            @if (old('public') === 1)
                                checked
                            @endif
                        >
                        <label for="public">Yes</label>
                    </span>

                    <span style="margin-left: 15px;">
                        <input
                            type="radio"
                            name="public"
                            value="0"
                            required
                            @if (old('public') === 0)
                                checked
                            @endif
                        >
                        <label for="public">No</label>
                    </span>
                </div>
                <div class="help-block with-errors" style="color: white; text-decoration: underline"></div>
            </div>
            <p>Once you submit this form, your event will display on the Home page sorted by event start date.</p>
            <p>Download the T-Mobile Sweepstakes Rules <a href="{{ url('/') }}/pdf/Sweepstakes_Rules_Combined.pdf" target="_blank">HERE</a> and fill in your event details at the bottom of the page. Please note that this HAS to be printed and displayed at your event.</p>
            <button type="submit" class="btn btn-primary btn-tmobile">Submit</button>
            <a href="@if(\Auth::user()->hasAdminRole()){{ route('admin.home') }}@else{{ route('home') }}@endif" class="btn btn-primary btn-tmobile">Cancel</a>
        </form>

    </div>
</div>

<script type="text/javascript">
    $(function () {
        $(".date").flatpickr({
            dateFormat: "m/d/Y",
            allowInput: true
        });
    });
</script>
@endsection
