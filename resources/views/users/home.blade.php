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
                <li>Only the person that CREATED the event can Edit Event Details and generate the report of the randomly selected Winner(s).</li>
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

                <div class="action-legend">
                    <div><i class="fas fa-trophy"></i> Pick Winners</div>
                    <div><i class="fas fa-edit"></i> Edit Event</div>
                    <div><i class="fas fa-poll"></i> Take Survey</div>
                    <div><span style="font-weight:bold">QR</span> Show QR Code</div>
                    <div><i class="fas fa-print"></i> Print QR Code</div>
                    <div><i class="fas fa-arrow-alt-circle-down"></i> Retrieve Survey Data</div>
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
                    <p style="color:#e20074">A .csv (an Excel document) file with all retrieved data from this event
                        will be sent to the user "{{ \Auth::user()->email }}" who created this event.
                        To proceed please click Generate Report below. It may take a few minutes for the email to come through.
                    </p>
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

        $(function() {
            // // Implements the dataTables plugin on the HTML table
            var $dTable= $("#events-table").dataTable( {
                processing: true,
                serverSide: true,
                responsive: true,
                "autoWidth": false,
                "bStateSave": false,
                "dom":' <"table-wrapper" <"#filter-wrapper.float-right"f>t<"#pagination-wrapper"p>>',
                "aaSorting": [[0,'asc']],
                "language": {
                    "search": "Search Event Details "
                },
                ajax: {
                    url: "{!! route('datatables.events') !!}",
                    data: function (d) {
                        d.title = $('#events-table_filter > label > input[type="search"]').val();
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
            {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            $('#winners-select-modal').on('show.bs.modal', function(e) {
                $('#winners-event-id').val($(e.relatedTarget).data('event-id'));
                $('#previous-winners-event-id').val($(e.relatedTarget).data('event-id'));
            });
            $('#event-report-modal').on('show.bs.modal', function(e) {
                $('#report-event-id').val($(e.relatedTarget).data('event-id'));
            });
            $('#survey-report-modal').on('show.bs.modal', function(e) {
                $('#survey-event-id').val($(e.relatedTarget).data('event-id'));
            });
        });

    </script>
@endsection
