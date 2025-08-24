@extends('backend.layout.main')
@section('content')

    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card" style="padding: 7px;">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h2 class="listH2">Purchase Invoices Received</h2>
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
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Supplier</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    <th>Received At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($receivedList as $key => $rec)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $rec->invoice->pi_number }}</td>
                                    <td>{{ $rec->invoice->order->supplier->name ?? '-' }}</td>
                                    <td>{{ $rec->received_notes ?? '-' }}</td>
                                    <td>
                                        @if($rec->status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-success">Completed</span>
                                        @endif
                                    </td>
                                    <td>{{ $rec->created_at->format('d M, Y') }}</td>
                                    <td>
                                        @if($rec->status == 0)
                                            <form action="{{ route('purchase.invoice.received.approve', $rec->id) }}" method="POST">
                                                @csrf
                                                <div class="">
                                                    <select name="warehouse_id" class="form-select form-select-sm me-2" required>
                                                        <option value="">-- Select Warehouse --</option>
                                                        @foreach($warehouses as $wh)
                                                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                </div>
                                            </form>
                                        @else
                                            <span class="badge bg-success">Approved</span>
                                        @endif
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Purchase Received yet.</td>
                                </tr>
                            @endforelse
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














{{--@extends('backend.layout.main')--}}

{{--@section('content')--}}
{{--<div class="container-fluid">--}}
{{--    <h2>Purchase Invoices Received</h2>--}}

{{--    @if(session('success'))--}}
{{--        <div class="alert alert-success">{{ session('success') }}</div>--}}
{{--    @endif--}}

{{--    <div class="card">--}}
{{--        <div class="card-body">--}}
{{--            <table class="table table-bordered table-striped">--}}
{{--                <thead class="table-dark">--}}
{{--                    <tr>--}}
{{--                        <th>#</th>--}}
{{--                        <th>Invoice</th>--}}
{{--                        <th>Supplier</th>--}}
{{--                        <th>Notes</th>--}}
{{--                        <th>Status</th>--}}
{{--                        <th>Received At</th>--}}
{{--                        <th>Action</th>--}}
{{--                    </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                    @forelse($receivedList as $key => $rec)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $key+1 }}</td>--}}
{{--                            <td>{{ $rec->invoice->pi_number }}</td>--}}
{{--                            <td>{{ $rec->invoice->order->supplier->name ?? '-' }}</td>--}}
{{--                            <td>{{ $rec->received_notes ?? '-' }}</td>--}}
{{--                            <td>--}}
{{--                                @if($rec->status == 0)--}}
{{--                                    <span class="badge bg-warning">Pending</span>--}}
{{--                                @else--}}
{{--                                    <span class="badge bg-success">Completed</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>{{ $rec->created_at->format('d M, Y') }}</td>--}}
{{--                            <td>--}}
{{--                                @if($rec->status == 0)--}}
{{--                                    <form action="{{ route('purchase.invoice.received.approve', $rec->id) }}" method="POST">--}}
{{--                                        @csrf--}}
{{--                                        <div class="d-flex">--}}
{{--                                            <select name="warehouse_id" class="form-select form-select-sm me-2" required>--}}
{{--                                                <option value="">-- Select Warehouse --</option>--}}
{{--                                                @foreach($warehouses as $wh)--}}
{{--                                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                @else--}}
{{--                                    <span class="badge bg-success">Approved</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}


{{--                        </tr>--}}
{{--                    @empty--}}
{{--                        <tr>--}}
{{--                            <td colspan="6" class="text-center">No Purchase Received yet.</td>--}}
{{--                        </tr>--}}
{{--                    @endforelse--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--@endsection--}}
