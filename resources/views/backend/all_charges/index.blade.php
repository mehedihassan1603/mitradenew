@extends('backend.layout.main')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

@section('content')
    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card px-5 py-5">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h4>All Charges List</h4>
                        </div>
                        <div class="align-items-center col">
                            <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn btn-sm btn-primary">Create</button>
                        </div>
                    </div>
                    <hr class="bg-secondary"/>
                    <div class="table-responsive">
                        <table class="table" id="countryTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{-- data ekhane asbe --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> {{--End list form--}}

    {{--Create form--}}
    <div class="modal animated zoomIn countryModel" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form id="country-store-form">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Create Charges List</h6>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Charges Name *</label>
                                    <input type="text" name="name" class="form-control" id="countryName" required>
                                    <span class="text-danger error-text country_name_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modal-close" class="btn bg-gradient-primary btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div> {{--End create form--}}


    {{-- Edit Update form--}}
    <div class="modal animated zoomIn" id="edit-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form id="country-update-form">
                    <input type="hidden" name="country_id" id="countryId"> {{-- hidden input--}}
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Edit Form </h6>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Name *</label>
                                    <input type="text" name="name_update" class="form-control" id="countryNameUpdate">
                                    <span class="text-danger error-text country_name_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="update-modal-close" class="btn bg-gradient-primary btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div> {{--End edit update form--}}


    {{--Delete form--}}
    <div class="modal animated zoomIn" id="delete-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3 class=" mt-3 text-warning">Delete !</h3>
                    <p class="mb-3">Once delete, you can't get it back.</p>
                    <input class="d-none" id="deleteID"/>

                </div>
                <div class="modal-footer justify-content-end">
                    <div>
                        <button type="button" id="delete-modal-close" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmDelete" class="btn bg-gradient-primary btn-danger" >Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>{{--End delete form--}}


@endsection


@push('scripts')


    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



    <script>

        $(document).ready(function() {

            // create/store country
            $('#country-store-form').on('submit', function(e) {
                e.preventDefault();
                $('#country-store-form').find('span.error-text').text('');

                let name = $('#countryName').val();

                $.ajax({
                    url: "{{ route('all.charges.store') }}",
                    method: "POST",
                    data: {
                        name: name,
                    },
                    success: function(response) {
                        // console.log(response);
                        toastr.success(response.message);
                        document.getElementById('modal-close').click();
                        document.getElementById('country-store-form').reset();
                        $('#countryTable').DataTable().ajax.reload();
                    },
                    error: function(data) {
                        if (data.status === 422) {
                            $.each(data.responseJSON.errors, function(field, messages) {
                                $('#country-store-form').find('span.' + field + '_error').text(messages[0]);
                            });
                        } else {
                            toastr.error("Something went wrong!");
                        }
                    }
                });
            }); // End Create Country


            // Country List show
            $('#countryTable').DataTable({

                processing: true,
                serverSide: true,

                ajax: "{{ route('all.charges.list') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'actions', name: 'actions' }
                ],

                pageLength:10,
                aLengthMenu:[[5,10,25,50,-1],[5,10,25,50,'All']],

            });// End Country List show

            // Edit Country
            $(document).on('click', '#editCountryBtn', function () {
                const id = $(this).data('id');
                $('#country-update-form').find('span.error-text').text('');

                $.ajax({
                    url: "{{ route('all.charges.edit') }}",
                    type: 'GET',
                    data: { id: id },
                    success: function (res) {
                        const data = res.data;

                        $('#countryId').val(data.id);
                        $('[name="name_update"]').val(data.name);
                        $('#edit-modal').modal('show');
                    },
                    error: function () {
                        alert('Failed to fetch country data.');
                    }
                });
            }); // End edit method


             // Update Country
            $('#country-update-form').on('submit', function(e) {
                e.preventDefault();
                $('#country-update-form').find('span.error-text').text('');

                let id = $('#countryId').val();
                let name = $('#countryNameUpdate').val();

                $.ajax({
                    url: "{{ route('all.charges.update') }}",
                    method: "POST",
                    data: {
                        id: id,
                        name: name,
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        document.getElementById('update-modal-close').click();
                        document.getElementById('country-update-form').reset();
                        $('#countryTable').DataTable().ajax.reload();
                    },
                    error: function(data) {
                        if (data.status === 422) {
                            $.each(data.responseJSON.errors, function(field, messages) {
                                $('#country-update-form').find('span.' + field + '_error').text(messages[0]);
                            });
                        } else {
                            toastr.error("Something went wrong!");
                        }
                    }
                });
            });  // End update method


            // Delete Country
            $(document).on('click', '#deleteCountryBtn', function () {

                // Delete modal show
                let id = $(this).data('id');
                $('#deleteID').val(id);
                $('#delete-modal').modal('show');
            });

            // Confirm delete
            $(document).on('click', '#confirmDelete', function () {
                let id = $('#deleteID').val();

                $.ajax({
                    url: "{{ route('all.charges.delete') }}",
                    method: 'POST',
                    data: {id: id},
                    success: function (response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $('#delete-modal').modal('hide');
                            $('#countryTable').DataTable().ajax.reload(); // Refresh table
                        } else {
                            toastr.error(response.message || 'Something went wrong!');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            toastr.error("Validation error.");
                        } else {
                            toastr.error("Unexpected error occurred.");
                        }
                    }
                });
            }); // End delete method







        });
    </script>
@endpush






{{--@extends('backend.layout.main')--}}

{{--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">--}}
{{--<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />--}}

{{--@section('content')--}}
{{--    <section class="forms">--}}
{{--        <div class="container-fluid">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header d-flex align-items-center">--}}
{{--                            <h4>{{trans('Add Charges')}}</h4>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <form action="{{ route('all.charges.store') }} " method="POST">--}}
{{--                                @csrf--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label style="font-size: 16px;">{{trans('Charges Name')}}</label>--}}
{{--                                            <input type="text" name="name" class="form-control" id="name" aria-describedby="name" required>--}}
{{--                                            <span class="validation-msg" id="name-error"></span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="align-items-center col">--}}
{{--                                    <button type="submit" class="float-left btn btn-primary">Create Charges</button>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

{{--    --}}{{--List form start--}}
{{--    <div class="container-fluid">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12 col-sm-12 col-lg-12">--}}
{{--                <div class="card px-5 py-5">--}}
{{--                    <div class="row justify-content-between ">--}}
{{--                        <div class="align-items-center col">--}}
{{--                            <h2>All Charges List</h2>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <hr class="bg-secondary"/>--}}
{{--                    <div class="table-responsive">--}}
{{--                        <table class="table" id="requisitionTable">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>ID</th>--}}
{{--                                <th>Name</th>--}}
{{--                                <th>Action</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach($allCharges as $allCharge)--}}
{{--                                <tr>--}}
{{--                                    <td>{{ $loop->iteration }}</td>--}}
{{--                                    <td>{{ $allCharge->name }}</td>--}}
{{--                                    <td>--}}
{{--                                        <a href="" id="editBtn" class="btn btn-sm btn-primary">Edit</a>--}}
{{--                                        <a href="#" class="btn btn-sm btn-danger">Delete</a>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--End list form--}}

{{--    --}}{{-- Edit Update form--}}
{{--    <div class="modal animated zoomIn" id="edit-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-dialog-centered modal-md">--}}
{{--            <div class="modal-content">--}}
{{--                <form id="country-update-form">--}}
{{--                    <input type="hidden" name="country_id" id="countryId"> --}}{{-- hidden input--}}
{{--                    <div class="modal-header">--}}
{{--                        <h6 class="modal-title" id="exampleModalLabel"> Country Edit Form </h6>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <div class="container">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-12 p-1">--}}
{{--                                    <label class="form-label">Country Name *</label>--}}
{{--                                    <input type="text" name="country_name_update" class="form-control" id="countryNameUpdate">--}}
{{--                                    <span class="text-danger error-text country_name_error"></span>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 p-1">--}}
{{--                                    <label class="form-label">Capital Name *</label>--}}
{{--                                    <input type="text" name="capital_city_update" id="capitalCityUpdate" class="form-control">--}}
{{--                                    <span class="text-danger error-text capital_city_error"></span>--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" id="update-modal-close" class="btn bg-gradient-primary btn-danger" data-bs-dismiss="modal">Close</button>--}}
{{--                        <button type="submit" class="btn btn-primary">Update</button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div> --}}{{--End edit update form--}}




{{--@endsection--}}

{{--@push('scripts')--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>--}}

{{--    <script>--}}
{{--        $(document).ready(function(){--}}
{{--            $('#editBtn').on('click',function (){--}}

{{--                $('#edit-modal').modal('show');--}}

{{--            });--}}

{{--        })--}}





{{--    </script>--}}
{{--@endpush--}}
