@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1>Clients</h1>
            </div>

            <table class="table table-striped table-bordered" style="width:100%" id="clients-table">
                <thead>
                <tr>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Secret</th>
                    <th>Callback</th>
                    <th>Password Grant</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
        <br>
    </div>
</div>

<!-- Delete Client Form -->
<div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2>Are you sure you want to delete this client?</h2>
                <form id="delete-client-form" method="POST" class="form" action="{{ route('admin.clients.delete') }}">
                    @csrf
                    <input type="hidden" id="delete-client-id" name="client_id">
                    <button id="delete-submit" type="submit" class="btn btn-primary btn-tmobile-modal btn-ok float-left">Delete</button>
                </form>
                <button id="delete-cancel" type="button" class="btn btn-primary btn-tmobile-modal float-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Form submission success -->
<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="thankYouModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <h2 id="success-response-text"></h2>
                    <button type="button" data-dismiss="modal" class="btn btn-primary btn-tmobile-modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form submission error -->
<div class="modal fade" id="error-response-modal" tabindex="-1" role="dialog" aria-labelledby="errorResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <h2 id="error-response-text"></h2>
                    <button type="button" data-dismiss="modal" class="btn btn-primary btn-tmobile-modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        // Implements the dataTables plugin on the HTML table
        const dTable = $("#clients-table").DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            "autoWidth": false,
            "bStateSave": false,
            "dom": '',
            "aaSorting": [[0, 'asc']],
            ajax: {
                url: "{!! route('datatables.clients') !!}",
                data: function (d) {
                    d.api_token = "{{ \Auth::user()->api_token }}"
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'client_name'},
                {data: 'secret', name: 'secret'},
                {data: 'redirect', name: 'redirect'},
                {data: 'password_client', name: 'password_client'},
                {data: 'action', name: 'action'}
            ]
        });

        // Delete Client
        const deleteUserForm = $("#delete-client-form");
        const submitDeleteButton = $("#delete-submit");
        const cancelDeleteButton = $("#delete-cancel");

        submitDeleteButton.on('click', function(event) {
            event.preventDefault();
            $("#success-response-text").empty();

            // Submit Form
            $.ajax({
                type: "POST",
                url: "{!! route('admin.clients.delete') !!}",
                data: {
                    contentType: 'application/json',
                    client_id: $('#delete-client-id').val(),
                    api_token: "{!! \Auth::user()->api_token !!}"
                },
                success: function (data) {
                    // Clear inputs

                    // Hide modal
                    $('#confirm-delete-modal').modal('hide');

                    // Display success modal.
                    document.getElementById("success-response-text").innerHTML = 'Client Revoked.';
                    $('#success-modal').modal('show');

                    dTable.clear().draw();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#confirm-delete-modal').modal('hide');
                    // Display error response modal with message.
                    const errorMessage = XMLHttpRequest.responseJSON.message;
                    document.getElementById("error-response-text").innerHTML = errorMessage;
                    $('#error-response-modal').modal('show');
                }
            });
        });

        cancelDeleteButton.on('click', function(event) {
            // Hide modal
            $('#confirm-delete-modal').modal('hide');
        });

        $('#confirm-delete-modal').on('show.bs.modal', function(e) {
            $('#delete-client-id').val($(e.relatedTarget).data('client-id'));
        });
    });
</script>
@endsection
