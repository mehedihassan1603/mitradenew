@extends('backend.layout.main')

@section('content')

    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card" style="padding: 7px;">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h2 class="listH2">Purchase Requisition List</h2>
                        </div>
                        <div class="align-items-center col">
                            <a href="{{ route('purchase.requisition.create') }}" class="float-right btn btn-primary">Create</a>
                        </div>
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
                                        <a href="{{ route('purchase.requisition.print',['id' => $req->id ]) }}" class="btn btn-sm btn-primary">View</a>
                                        <a href="{{ route('purchase.requisition.edit',['id' => $req->id ]) }}" class="btn btn-sm btn-primary">Edit</a>
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

    <script>
        $(document).ready(function () {
            $('#requisitionTable').DataTable({
                paging: true,
                searching: true,
                ordering:  true
            });
        });
    </script>

@endpush
