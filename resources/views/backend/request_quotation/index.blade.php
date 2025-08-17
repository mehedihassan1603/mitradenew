
@extends('backend.layout.main')

@section('content')

    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card px-5 py-5">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h2>Request for Quotation Lists</h2>
                        </div>
{{--                        <div class="align-items-center col">--}}
{{--                            <a href="{{ route('purchase.requisition.create') }}" class="float-right btn-primary">Create</a>--}}
{{--                        </div>--}}
                    </div>
                    <hr class="bg-secondary"/>
                    <div class="table-responsive">
                        <table class="table" id="requisitionTable">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Ref Id</th>
                                <th>Total Quantities</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requisitions as $req)
                                @php
                                    // JSON decode quantities যদি array হয়ে থাকে
                                    $quantities = is_array($req->quantities) ? $req->quantities : json_decode($req->quantities, true);
                                    $totalQuantity = $quantities ? array_sum($quantities) : $req->quantities;
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($req->date)->format('d-M-Y') }}</td>
                                    <td>{{ $req->ref_id ?? 'N/A' }}</td>
                                    <td>{{ $totalQuantity }}</td>
                                    <td>
                                       <td>
    <a href="{{ route('purchase.requisition.print',['id' => $req->id ]) }}" class="btn btn-sm btn-primary">View</a>
    <a href="{{ route('request.quotation.create', $req->id) }}" class="btn btn-sm btn-success">Add Supplier Quote</a>
    <a href="{{ route('foreign-quotations.compare', $req->id) }}" class="btn btn-sm btn-warning">
        Compare Quotations
    </a>

</td>

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
