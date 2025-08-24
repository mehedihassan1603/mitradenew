
@extends('backend.layout.main')

@section('content')

    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="padding: 7px;">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h2 class="listH2">Request Quotation List</h2>
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
                                    <th>Suppliers</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requisitions as $req)
                                    @php
                                        $quantities = is_array($req->quantities) ? $req->quantities : json_decode($req->quantities, true);
                                        $totalQuantity = $quantities ? array_sum($quantities) : $req->quantities;
                                    @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($req->date)->format('d-M-Y') }}</td>
                                        <td>{{ $req->ref_id ?? 'N/A' }}</td>
                                        <td>{{ $totalQuantity }}</td>

                                        <td>
                                            @if($req->suppliers && $req->suppliers->count())
                                                {{ $req->suppliers->pluck('supplier.name')->implode(', ') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('purchase.requisition.print',['id' => $req->id ]) }}" class="btn btn-sm btn-primary">View</a>
                                            <a href="{{ route('request.quotation.create', $req->id) }}" class="btn btn-sm btn-success">Add Supplier</a>
                                            <a href="{{ route('foreign-quotations.compare', $req->id) }}" class="btn btn-sm btn-warning">Compare</a>
                                            <a href="{{ route('request-quotation.delete', $req->id) }}" class="btn btn-sm btn-danger mt-1"  onclick="return confirm('Are you sure you want to delete this requisition?')">Delete</a>
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
