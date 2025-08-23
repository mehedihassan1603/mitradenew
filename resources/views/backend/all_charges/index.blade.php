@extends('backend.layout.main')

{{--@section('content')--}}

{{--    @extends('backend.layout.main')--}}

{{--    @if(in_array('ecommerce',explode(',',$general_setting->modules)) || in_array('restaurant',explode(',',$general_setting->modules)))--}}
{{--        @push('css')--}}
{{--            <style>--}}
{{--                .search_result, .search_result_addon {border:1px solid #e4e6fc;border-radius:5px;overflow-y: scroll;}--}}
{{--                .search_result > div, .search_result_addon > div, .selected_items > div, .selected_addons > div {border-top:1px solid #e4e6fc;cursor:pointer;display:flex;align-items:center;padding: 10px;position: relative;}--}}
{{--                .search_result > div > img, .search_result_addon > div > img, .selected_items > div > img, .selected_addons > div > img {margin-right: 10px;max-width: 40px;}--}}
{{--                .search_result > div h4, .search_result_addon > div h4, .selected_items > div h4, .selected_addons > div h4 {font-size: 0.9rem;}--}}
{{--                .search_result > div i,  .search_result_addon > div i, {color:#54b948;position:absolute;right:5px;top:30%}--}}
{{--                .search_result div:first-child, .search_result_addon div:first-child, {border-top:none}--}}
{{--                .selected_items .remove_item, .selected_addons .remove_item {position: absolute;right: 20px;top:20px};--}}
{{--                .delVarOption{display: flex;flex-direction: column;align-items: center;}--}}
{{--            </style>--}}
{{--        @endpush--}}
{{--    @endif--}}
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{trans('Add Charges')}}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('all.charges.store') }} " method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{trans('Charge Name')}}</label>
                                            <input type="text" name="name" class="form-control" id="name" aria-describedby="name" required>
                                            <span class="validation-msg" id="name-error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center col">
                                    <button type="submit" class="float-left btn btn-primary">Create Charges</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card px-5 py-5">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h2>All Charges List</h2>
                        </div>
                    </div>
                    <hr class="bg-secondary"/>
                    <div class="table-responsive">
                        <table class="table" id="requisitionTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allCharges as $allCharge)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $allCharge->name }}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--End list form--}}
@endsection

@push('scripts')

    {{--  bootstrap css and js  --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{--  data table css and js  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>







@endpush
