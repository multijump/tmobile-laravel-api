@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 intro-text-list">
            <h1>Events</h1>
            <ul>
                <li>This tool allows your team to electronically gather customer and prospect information while at events. This gets rid of our "fishbowl" drawings because the tool can choose winners for your contests, sweepstakes and giveaways.</li>
                <li>You can create a new event using the button to your left or open an existing event by using the search feature to the right.</li>
                <li>ONLY CREATE ONE EVENT REGISTRATION PER EVENT. Multiple devices and logins can be used to add data to the same event.</li>
                <li>Only the person that created the event can Edit Event Details and use the Select Winner(s) feature.</li>
                <li>The tool only supports events that are 3 days or shorter. If your event lasts longer than 3 days, you’ll need to create separate event entries.</li>
                <li>You'll need to download the T-Mobile Sweepstakes Rules <a href="{{ url('/') }}/pdf/Sweepstakes_Rules_Combined.pdf" target="_blank">HERE</a> and fill in your event details at the bottom of the page. Please note that this HAS to be printed and displayed at your event.</li>
            </ul>
        </div>
    </div>

    <div class="row justify-content-center table-row">
        <div class="col-md-12">
            <a href="{{ route('events.create') }}" class="btn btn-primary btn-tmobile" id="btn-create-event">Create Event</a>
            <table class="table table-bordered" style="width:100%" id="events-table">
                <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Title</th>
                    <th>Store Number</th>
                    <th>Region</th>
                    <th>State</th>
                    <th>Participants</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>

            {{--<div class="row">--}}
                {{--<div class="dateControlBlock">--}}
                    {{--Between <input type="text" name="start_date" id="start-date" class="datepicker" value="" size="8" /> and--}}
                    {{--<input type="text" name="end_date" id="end-date" class="datepicker" value="" size="8"/>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="action-legend">
                <div><i class="fas fa-trophy"></i> Pick Winners</div>
                <div><i class="fas fa-file-download"></i> Retrieve Event Data</div>
                <div><i class="fas fa-edit"></i> Edit Event</div>
                <div><i class="fas fa-poll"></i> Take Survey</div>
                <div><span style="font-weight:bold">QR</span> Show QR Code</div>
                <div><i class="fas fa-print"></i> Print QR Code</div>
                <div><i class="fas fa-arrow-alt-circle-down"></i> Retrieve Survey Data</div>
                <div><i class="fas fa-trash-alt"></i> Archive Event</div>
            </div>

        </div>
        <br>

    </div>
</div>

    <div class="modal fade" id="winners-select-modal" tabindex="-1" role="dialog" aria-labelledby="winnerSelectModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="color:#e20074">Pick Winners?</p>
                    <form method="POST" action="{{ route('events.winners.select') }}">
                        @csrf
                        <div class="form-group">
                            <select name="winner_count" id="winner-count" class="form-control">
                                <option value="">Select Number Of Winners</option>
                                <option value="1">1 Winner</option>
                                @for($i = 2; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} Winners</option>
                                @endfor
                            </select>
                        </div>
                        <br>
                        <input type="hidden" id="winners-event-id" name="event_id">
                        <button type="submit" class="btn btn-primary btn-tmobile-modal btn-ok" style="width: 100%; margin-top: 5px; margin-bottom: 5px">Pick Winners</button>
                    </form>
                    <form method="POST" action="{{ route('events.winners.select') }}">
                        @csrf
                        <input type="hidden" id="winner-count" name="winner-count" value="0">
                        <input type="hidden" id="previous-winners-event-id" name="event_id">
                        <button type="submit" class="btn btn-primary btn-tmobile-modal btn-ok" style="width: 100%; margin-top: 5px; margin-bottom: 5px">Show Previous Winners</button>
                    </form>
                    <button type="button" class="btn btn-primary btn-tmobile-modal" style="width: 100%; margin-top: 5px; margin-bottom: 5px" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="event-report-modal" tabindex="-1" role="dialog" aria-labelledby="eventReportModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h1>Retrieve Event Data</h1>
                <p>A password protected .zip file of an Excel document with all retrieved data from this event will be sent to "{{ \Auth::user()->email }}".
                    To proceed please click Generate Report below. It may take a few minutes for the email to come through.</p>
                    <br>
                <form method="POST" action="{{ route('reports.event') }}">
                    @csrf
                    <input type="hidden" id="report-event-id" name="event_id">
                    <button type="submit" class="btn btn-primary btn-tmobile-modal btn-ok float-left">Generate Report</button>
                </form>
                <button type="button" class="btn btn-primary btn-tmobile-modal float-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="event-delete-modal" tabindex="-1" role="dialog" aria-labelledby="eventDeleteModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h1>Archive Event Data</h1>
                <p>This event will be archived, and will no longer appear in this web interface or in historical exports.
                    To proceed please click Archive Event below.</p>
                    <br>
                <form method="POST" action="{{ route('events.archive') }}">
                    @csrf
                    <input type="hidden" id="delete-event-id" name="event_id">
                    <button type="submit" class="btn btn-primary btn-tmobile-modal btn-ok float-left">Archive Event</button>
                </form>
                <button type="button" class="btn btn-primary btn-tmobile-modal float-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="survey-report-modal" tabindex="-1" role="dialog" aria-labelledby="surveyReportModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h1>Retrieve Survey Data</h1>
                <p>A password protected .zip file of an Excel document with all retrieved data from this event will be sent to "{{ \Auth::user()->email }}".
                    To proceed please click Generate Report below. It may take a few minutes for the email to come through.</p>
                    <br>
                <form method="POST" action="{{ route('reports.survey') }}">
                    @csrf
                    <input type="hidden" id="survey-event-id" name="event_id">
                    <button type="submit" class="btn btn-primary btn-tmobile-modal btn-ok float-left">Generate Report</button>
                </form>
                <button type="button" class="btn btn-primary btn-tmobile-modal float-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>

    // Uncomment to add date filtering
    // // The plugin function for adding a new filtering routine
//    $.fn.dataTableExt.afnFiltering.push(
//        function(oSettings, aData, iDataIndex){
//            var startDate = parseDateValue($("#start-date").val());
//            var endDate = parseDateValue($("#end-date").val());
//            // aData represents the table structure as an array of columns, so the script access the date value
//            // in the first column of the table via aData[0]
//            var evalDate= parseDateValue(aData[0]);
//            if (evalDate >= startDate && evalDate <= endDate) {
//                return true;
//            }
//            else {
//                return false;
//            }
//        });
    // // Function for converting a mm/dd/yyyy date value into a numeric string for comparison (example 08/12/2010 becomes 20100812
//    function parseDateValue(rawDate) {
//        var dateArray= rawDate.split("/");
//        var parsedDate= dateArray[2] + dateArray[0] + dateArray[1];
//        return parsedDate;
//    }

$(function() {
    // // Implements the dataTables plugin on the HTML table
    var $dTable= $("#events-table").dataTable( {
        processing: true,
        serverSide: true,
        responsive: true,
        "autoWidth": false,
//            "iDisplayLength": 10,
        "bStateSave": false,
//            "oLanguage": {
//                "sLengthMenu": 'Show <select><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="200">200</option></select> entries'
//            },
        "dom":' <"table-wrapper" <"#filter-wrapper.float-right"f>t<"#pagination-wrapper"p>>',
        "aaSorting": [[0,'asc']],
        "language": {
            "search": "Search Event Details "
        },
        ajax: {
            url: "{!! route('datatables.events') !!}",
            data: function (d) {
                d.title = $('#events-table_filter > label > input[type="search"]').val();
//                    d.start_date = $('input[name=start_date]').val();
//                    d.end_date = $('input[name=end_date]').val();
            }
        },
        columns: [
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'title', name: 'title' },
            { data: 'store_number', name: 'store_number' },
            { data: 'region', name: 'region' },
            { data: 'state', name: 'state' },
            { data: 'participants', name: 'participants' },
//            {data: 'pick_winner', name: 'pick_winner', width:'150px', orderable: false, searchable: false},
//            {data: 'retrieve_data', name: 'retrieve_data', width:'150px', orderable: false, searchable: false},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // // The dataTables plugin creates the filtering and pagination controls for the table dynamically, so these
    // // lines will clone the date range controls currently hidden in the baseDateControl div and append them to
    // // the feedbackTable_filter block created by dataTables
    // // $dateControls= $("#baseDateControl").children("div").clone();
    // // $("#feedbackTable_filter").prepend($dateControls);
    // // Implements the jQuery UI Datepicker widget on the date controls
//        $('.datepicker').datepicker(
//            {
//                showOn: 'button',
//                buttonImage: "//jqueryui.com/resources/demos/datepicker/images/calendar.gif",
//                buttonImageOnly: true
//            }
//        );
    // // Create event listeners that will filter the table whenever the user types in either date range box or
    // // changes the value of either box using the Datepicker pop-up calendar
//        $("#start-date").keyup ( function() { $dTable.fnDraw(); } );
//        $("#start-date").change( function() { $dTable.fnDraw(); } );
//        $("#end-date").keyup ( function() { $dTable.fnDraw(); } );
//        $("#end-date").change( function() { $dTable.fnDraw(); } );


    $('#winners-select-modal').on('show.bs.modal', function(e) {
        $('#winners-event-id').val($(e.relatedTarget).data('event-id'));
        $('#previous-winners-event-id').val($(e.relatedTarget).data('event-id'));
    });
    $('#event-report-modal').on('show.bs.modal', function(e) {
        $('#report-event-id').val($(e.relatedTarget).data('event-id'));
    });
    $('#event-delete-modal').on('show.bs.modal', function(e) {
        $('#delete-event-id').val($(e.relatedTarget).data('event-id'));
    });
    $('#survey-report-modal').on('show.bs.modal', function(e) {
        $('#survey-event-id').val($(e.relatedTarget).data('event-id'));
    });
});

</script>
@endsection
