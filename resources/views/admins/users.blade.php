@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1>Users</h1>
            </div>

            <table class="table table-striped table-bordered" style="width:100%" id="users-table">
                <thead>
                <tr>
                </tr>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Approved</th>
                    <th>Denied</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
        <br>
    </div>
</div>

<!-- Delete user form -->
<div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2>Are you sure you want to delete this user?</h2>
                <form id="delete-user-form" method="POST" class="form" action="{{ route('admin.users.delete') }}">
                    @csrf
                    <input type="hidden" id="delete-user-id" name="user_id">
                    <button id="delete-submit" type="submit" class="btn btn-primary btn-tmobile-modal btn-ok float-left">Delete</button>
                </form>
                <button id="delete-cancel" type="button" class="btn btn-primary btn-tmobile-modal float-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Approve user as admin form -->
<div class="modal fade" id="confirm-approve-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2>Are you sure you want to make this user an admin?</h2>
                <form id="approve-user-form" method="POST" class="form" action="{{ route('admin.users.promote') }}">
                    @csrf
                    <input type="hidden" id="approve-user-id" name="user_id">
                    <button id="approve-submit" type="submit" class="btn btn-primary btn-tmobile-modal btn-ok float-left">Approve</button>
                </form>
                <button id="approve-cancel" type="button" class="btn btn-primary btn-tmobile-modal float-right" data-dismiss="modal">Cancel</button>
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
        // // Implements the dataTables plugin on the HTML table
        var dTable= $("#users-table").DataTable( {
            processing: true,
            serverSide: true,
            responsive: true,
            "autoWidth": false,
            "bStateSave": false,
            "dom":' <"table-wrapper" <"#filter-wrapper" f>t<"#pagination-wrapper"p>>',
            "aaSorting": [[0,'asc']],
            "language": {
                "search": "Search Name / Email # "
            },
            ajax: {
                url: "{!! route('datatables.users') !!}",
                data: function (d) {
                    d.keyword = $('#users-table_filter > label > input[type="search"]').val();
                    d.api_token = "{{ \Auth::user()->api_token }}"
                }
            },
            columns: [
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'approved_date', name: 'approved_date' },
                { data: 'denied_date', name: 'denied_date' },
                { data: 'role', name: 'is_admin' },
                {data: 'action', name: 'action'}
            ]
        });

        // Delete User
        var deleteUserForm = $("#delete-user-form");
        var submitDeleteButton = $("#delete-submit");
        var cancelDeleteButton = $("#delete-cancel");

        submitDeleteButton.on('click', function(event) {
            event.preventDefault();
            $("#success-response-text").empty();

            // Submit Form
            $.ajax({
                type: "POST",
                url: "{!! route('admin.users.delete') !!}",
                data: {
                    contentType: 'application/json',
                    user_id: $('#delete-user-id').val(),
                    api_token: "{!! \Auth::user()->api_token !!}"
                },
                success: function (data) {
                    // Clear inputs

                    // Hide modal
                    $('#confirm-delete-modal').modal('hide');

                    // Display success modal.
                    document.getElementById("success-response-text").innerHTML = 'User Deleted.';
                    $('#success-modal').modal('show');

                    dTable.clear().draw();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#confirm-delete-modal').modal('hide');
                    // Display error response modal with message.
                    var errorMessage = XMLHttpRequest.responseJSON.message;
                    document.getElementById("error-response-text").innerHTML = errorMessage;
                    $('#error-response-modal').modal('show');
                }
            });
        });

        cancelDeleteButton.on('click', function(event) {
            // Hide modal
            $('#confirm-delete-modal').modal('hide');
        });

        // Approve User
        var approveUserForm = $("#approve-user-form");
        var submitApproveButton = $("#approve-submit");
        var cancelApproveButton = $("#approve-cancel");

        submitApproveButton.on('click', function(event) {
            event.preventDefault();
            $("#success-response-text").empty();
            // Submit Form
            $.ajax({
                type: "POST",
                url: "{!! route('admin.users.promote') !!}",
                data: {
                    contentType: 'application/json',
                    user_id: $('#approve-user-id').val(),
                    api_token: "{!! \Auth::user()->api_token !!}"
                },
                success: function (data) {
                    // Clear inputs

                    // Hide modal
                    $('#confirm-approve-modal').modal('hide');

                    // Display success modal.
                    document.getElementById("success-response-text").innerHTML = 'User Approved as Admin.';
                    $('#success-modal').modal('show');

                    dTable.clear().draw();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    $('#confirm-approve-modal').modal('hide');
                    // Display error response modal with message.
                    var errorMessage = XMLHttpRequest.responseJSON.message;
                    document.getElementById("error-response-text").innerHTML = errorMessage;
                    $('#error-response-modal').modal('show');
                }
            });
        });

        cancelApproveButton.on('click', function(event) {
            event.preventDefault();
            $('#confirm-approve-modal').modal('hide');
        });


        $('#confirm-delete-modal').on('show.bs.modal', function(e) {
            $('#delete-user-id').val($(e.relatedTarget).data('user-id'));
        });
        $('#confirm-approve-modal').on('show.bs.modal', function(e) {
            $('#approve-user-id').val($(e.relatedTarget).data('user-id'));
        });

    });
</script>
@endsection
