@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h1>Edit Event</h1>
                </div>
                <form novalidate="true" data-toggle="validator" action="{{ route('events.update', $event->id) }}" method="POST" class="form">
                    @csrf
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="title">Event Name</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="title"  class="form-control" name="title" value="{{ old('title') ?: $event->title }}" required data-error="**Required Field">
                                <span class="help-block" style="font-size: .8em; color: #ffaacc">(Customers will see the event name — so please make sure the spelling and capitalization are correct. Ex: Ariana Grande Ticket vs. AG ticket giveaway)</span>
                            </div>
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="store_number">Store Number</label>
                            </div>
                            <div class="col-6">
                                <input type="text"  class="form-control" name="store_number" value="{{ old('store_number') ?: $event->store_number }}" required data-error="**Required Field">
                            </div>
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="store_number">Workfront ID</label>
                            </div>
                            <div class="col-6">
                                <input type="text"  class="form-control" name="workfront_id" value="{{ old('workfront_id') ?: $event->workfront_id }}">
                            </div>
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="start_date">Event Start Date</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control date" name="start_date" value="{{ old('start_date') ?: $event->getDatePickerStartDate() }}" required data-error="**Required Field">
                            </div>
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="end_date">Event End Date</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control date" name="end_date" value="{{ old('end_date') ?: $event->getDatePickerEndDate() }}" required data-error="**Required Field">
                            </div>
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="description">Event Description<br><span class="help-block" style="font-size: .8em; color: #ffaacc">(max. 500 characters)</span></label>
                            </div>
                            <div class="col-6">
                                <textarea id="description" class="form-control" name="description" maxlength="500" rows="3" required data-error=" **Required Field"></textarea>
                            </div>
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="region">Region</label>
                            </div>
                            <div class="col-6">
                                    <select id="region" class="form-control" name="region">
                                    <option value="Central">Central</option>
                                    <option value="East">East</option>
                                    <option value="South">South</option>
                                    <option value="West">West</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="state">State</label>
                            </div>
                            <div class="col-6">
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
                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div
                                class="col-3 text-right"
                                style="display: flex; justify-content: flex-end; align-items: center;"
                            >
                                <span>Event Type</span>
                            </div>
                            <div class="col-6">
                                <div>
                                    <input
                                        type="radio"
                                        name="company"
                                        value="T-Mobile"
                                        required
                                        @if (old('company') === 1 ?: $event->company == "T-Mobile")
                                            checked
                                        @endif
                                    >
                                    <label for="company">T-Mobile</label>
                                </div>

                                <div>
                                    <input
                                        type="radio"
                                        name="company"
                                        value="Metro"
                                        required
                                        @if (old('company') === 0 ?: $event->company == "Metro")
                                            checked
                                        @endif
                                    >
                                    <label for="company">Metro by T-Mobile</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div
                                class="col-3 text-right"
                                style="display: flex; justify-content: flex-end; align-items: center;"
                            >
                                <span>Exit survey will be used on-site</span>
                            </div>
                            <div class="col-6">
                                <div>
                                    <input
                                        type="radio"
                                        name="has_surveys_enabled"
                                        value="1"
                                        required
                                        @if (old('has_surveys_enabled') === 1 ?: $event->hasSurveysEnabled())
                                            checked
                                        @endif
                                    >
                                    <label for="has_surveys_enabled">Yes</label>
                                </div>

                                <div>
                                    <input
                                        type="radio"
                                        name="has_surveys_enabled"
                                        value="0"
                                        required
                                        @if (old('has_surveys_enabled') === 0 ?: !$event->hasSurveysEnabled())
                                            checked
                                        @endif
                                    >
                                    <label for="has_surveys_enabled">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div
                                class="col-3 text-right"
                                style="display: flex; justify-content: flex-end; align-items: center;"
                            >
                                <span>Does your event require hands free registration via QR code?</span>
                            </div>
                            <div class="col-6">
                                <div>
                                    <input
                                        type="radio"
                                        name="public"
                                        value="1"
                                        required
                                        @if (old('public') === 1 ?: $event->public == 1)
                                            checked
                                        @endif
                                    >
                                    <label for="public">Yes</label>
                                </div>

                                <div>
                                    <input
                                        type="radio"
                                        name="public"
                                        value="0"
                                        required
                                        @if (old('public') === 0 ?: $event->public == 0)
                                            checked
                                        @endif
                                    >
                                    <label for="public">No</label>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="help-block with-errors" style="color: red;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div
                                class="col-3 text-right"
                                style="display: flex; justify-content: flex-end; align-items: center;"
                            >
                                <span>Primary Language</span>
                            </div>
                            <div class="col-6">
                                <div>
                                    <input
                                        type="radio"
                                        name="default_language"
                                        value="English"
                                        required
                                        @if (old('default_language') === "English" ?: $event->default_language == "English")
                                            checked
                                        @endif
                                    >
                                    <label for="default_language">English</label>
                                </div>

                                <div>
                                    <input
                                        type="radio"
                                        name="default_language"
                                        value="0"
                                        required
                                        @if (old('default_language') === "Spanish" ?: $event->default_language == "Spanish")
                                            checked
                                        @endif
                                    >
                                    <label for="default_language">Spanish</label>
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
                                <label></label>
                            </div>
                            <div class="col-6">
                            <p>Download the T-Mobile Sweepstakes Rules <a href="{{ url('/') }}/pdf/Sweepstakes_Rules_Combined.pdf" target="_blank">HERE</a> and fill in your event details at the bottom of the page. Please note that this HAS to be printed and displayed at your event.</p>
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

    <script type="text/javascript">
        $(function () {
            $(".date").flatpickr({
                dateFormat: "m/d/Y",
                allowInput: true
            });
            if(("{{ old('region') ?: $event->region }}") !== ""){
                $('#region').val("{{$event->region}}");
            }
            if(("{{ old('state') ?: $event->state }}") !== ""){
                $('#state').val("{{$event->state}}");
            }
            if("{{ old('description') ?: $event->description }}"){
                $('#description').val(htmlDecode("{{ $event->description }}"));
            }
        });

        function htmlDecode(value) {
            return $("<textarea/>").html(value).text();
        }
    </script>
@endsection
