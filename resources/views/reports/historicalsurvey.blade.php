@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h1>Export Historical Survey Data</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p>A .csv file with all historical survey data will be sent to {{ \Auth::user()->email }}. To proceed please select date range and click Generate Report below. To pull complete data, leave date range blank. It may take a few minutes for the email to come through.</p>
            <br>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <form action="{{ route('reports.historicalsurvey.generate') }}" method="POST" class="form">
                    @csrf
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="start_date">Start Date</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control date" name="start_date" value="{{ old('start_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-3 text-right">
                                <label for="end_date">End Date</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control date" name="end_date" value="{{ old('end_date') }}">
                            </div>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary btn-tmobile">Submit</button>
                    <a href="@if(\Auth::user()->hasAdminRole()){{ route('admin.home') }}@else{{ route('home') }}@endif" class="btn btn-primary btn-tmobile">Cancel</a>
                </form>
            </div>
        </div>
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
