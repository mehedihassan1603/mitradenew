@extends('backend.layout.main')


@section('content')

    @if($errors->has('name'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('name') }}</div>
    @endif
    @if($errors->has('image'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ $errors->first('image') }}</div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if(session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif




    <section>
        <div class="container-fluid">
            <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#create-modal"><i class="dripicons-plus"></i> {{trans("Add HS Code")}}</button>&nbsp;
            <button class="btn btn-primary" data-toggle="modal" data-target="#importCategory"><i class="dripicons-copy"></i> {{trans('Import Add HS Code')}}</button>
        </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="card px-5 py-5">
                            <div class="row justify-content-between ">
                                <div class="align-items-center col">
                                    <h4>HS Code List</h4>
                                </div>
                            </div>
                            <hr class="bg-secondary"/>
                            <div class="table-responsive">
                                <table class="table" id="countryTable">
                                    <thead>
                                    <tr>
                                        <th>HS Code</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hscodes as $hscode)
                                            <tr>
                                                <td>{{ $hscode->name }}</td>
                                                <td>{{ $hscode->value }}</td>
                                                <td>{{ $hscode->status }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-success btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete...?');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{--End list form--}}
    </section>

    {{--Create form--}}
    <div class="modal animated zoomIn countryModel" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form id="hscode-store-form">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel">Create HS Code</h3>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">HS Code Name <span style="color:red;">*</span></label>
                                    <input type="text" name="hs_code_name" class="form-control" id="hsCodeName">
                                    <span class="text-danger error-text hs_code_name_error"></span>
                                </div>
                                <div class="col-12 p-1">
                                    <label class="form-label">Value</label>
                                    <input type="text" name="value" id="value" class="form-control">
                                </div>
                                <div class="col-12 p-1">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="" class="text-muted" selected disabled>HS Code Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
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


@endsection


@push('scripts')
    <script type="text/javascript">
        {{--$("ul#product").siblings('a').attr('aria-expanded','true');--}}
        {{--$("ul#product").addClass("show");--}}
        {{--$("ul#product #category-menu").addClass("active");--}}

        {{--// function confirmDelete() {--}}
        {{--//     if (confirm("If you delete category all products under this category will also be deleted. Are you sure want to delete?")) {--}}
        {{--//         return true;--}}
        {{--//     }--}}
        {{--//     return false;--}}
        {{--// }--}}

        {{--var category_id = [];--}}
        {{--var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;--}}

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // test jak start
        $(document).ready(function(){
            $('#modal-close').on('click',function(){
                $('#create-modal').modal('hide');
            }) // end close method

            // create/store country
            $('#hscode-store-form').on('submit', function(e) {
                e.preventDefault();
                $('#hscode-store-form').find('span.error-text').text('');

                let name = $('#hsCodeName').val();
                let value = $('#value').val();
                let status = $('#status').val();

                $.ajax({
                    url: "{{ route('hs.code.store') }}",
                    method: "POST",
                    data: {
                        name: name,
                        value: value,
                        status: status
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        document.getElementById('modal-close').click();
                        document.getElementById('hscode-store-form').reset();
                        // $('#countryTable').DataTable().ajax.reload();
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
                order: [],
                pageLength: 10,
                lengthMenu: [[5,10,25,50,-1],[5,10,25,50,'All']]
            });

        })

    </script>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@endpush
