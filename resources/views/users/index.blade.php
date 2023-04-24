@extends('layouts.layout')
@section('title', 'Users')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3"></div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                @if(Session('message'))
                <div class="alert alert-success">
                    {{ Session('message') }}
                </div>
                @endif
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-md-4">
                <h4 class="fw-bold"><span class="text-muted fw-light">Users /</span> Form</h4>
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-end">
                    <div class="search-main d-flex align-items-center me-4">
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input type="text" id="livesearch" class="form-control" placeholder="Search...">
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#usersModal">
                        Add Users
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="usersModalLabel">Add Users</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="add-users" id="addUsers" action="{{ route('add-users') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="form-label">First Name</label>
                                <div class="input-group">
                                    <input type="text" id="firstname" name="firstname" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="usersid" id="usersid"> 

                            <div class="form-group mb-3">
                                <label class="form-label">Last Name</label>
                                <div class="input-group">
                                    <input type="text" id="lastname" name="lastname" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="email" id="email" name="email" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Phone</label>
                                <div class="input-group">
                                    <input type="number" id="phone" name="phone" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Users Data</h5>
                    <div class="table-responsive text-nowrap" id="users_table">
                        
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('js')
<script>
    // Users Validation
    $('#addUsers').validate({
    rules: {
        firstname: {
            required: true
        },
        lastname: {
            required: true
        },
        email: {
            required: true
        },
        phone: {
            required: true
        },
        password: {
            required: true
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    }
    });

    // Edit Users
    $(document).on('click','.editusers', function () {
        var id = $(this).data('id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: 'users/' + id + '/edit-users',
            dataType: 'json',
            success: function (data) {
                if(data.status == 'true') {
                    var usersData = data.data
                    $('#usersid').val(usersData.id);
                    $('#firstname').val(usersData.firstname);
                    $('#lastname').val(usersData.lastname);
                    $('#email').val(usersData.email);
                    $('#phone').val(usersData.phone);
                }
            },
        });
    });

    // Search Users
    var qstring = 'searchusers=';
    getUsersData(qstring);
    $(document).on('keyup','#livesearch',function(){
        search = $(this).val();
        qstring = 'search='+ search;
        getUsersData(qstring);
        var query = $(this).val();

    });

    function getUsersData(qstring)
    {
        $.ajax({
            url: 'users?'+qstring,
            type: 'GET',
            dataType:'json',
            success:function(data)
            {
                $('#users_table').html(data.data);
            },
            error: function(e) {
            }
        });
    }

    // Delete Users
    $(document).on('click', '#deleteUsers', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        swal({
            title: 'Are you sure want to delete this Users?',
            icon: 'warning',
            buttons: ["Cancel", "Yes!"],
        })
        .then((Done) => {
            if(Done){
                usersDelete(id);
            }
        });
    });

    function usersDelete(id) {
        let url = "{{ route('delete-users', ':id') }}";
        url = url.replace(':id', id);
        $.ajax({
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: url,
            success: function(data) {
                if(data.status == 200){
                    getUsersData(qstring);
                    swal({
                        title: "Users deleted succsessfully",
                        icon: "success",
                        timer: 1500
                    });
                }
            }
        });
    };
</script>

@endsection