@extends('backend.layout.main')

@section('content')

    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card" style="padding: 7px;">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h2 class="listH2">Purchase Orders</h2>
                        </div>
                        {{-- <div class="align-items-center col">--}}
                        {{--     <a href="#" class="float-right btn btn-primary">Create</a>--}}
                        {{-- </div>--}}
                    </div>
                    <hr class="bg-secondary"/>
                    <div class="table-responsive">
                        <table class="table" id="requisitionTable">
                            <thead>
                                <tr>
                                    <th>PO ID</th>
                                    <th>Supplier</th>
                                    <th>Exporter</th>
                                    <th>Order Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseOrders as $po)
                                    <tr>
                                        <td>PO-{{ $po->id }}</td>
                                        <td>{{ $po->supplier->name ?? '-' }}</td>
                                        <td>{{ $po->exporter->name ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M, Y') }}</td>
                                        <td>{{ number_format($po->total_amount, 2) }}</td>
                                        <td>{{ ucfirst($po->status) }}</td>
                                        <td>
                                            <a href="{{ route('purchase.order.show', $po->id) }}" class="btn btn-sm btn-primary">View</a>
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







{{--@extends('backend.layout.main')--}}

{{--@section('content')--}}
{{--<div class="container">--}}
{{--    <h2>Purchase Orders</h2>--}}

{{--    <table class="table table-bordered table-striped">--}}
{{--        <thead>--}}
{{--            <tr>--}}
{{--                <th>PO ID</th>--}}
{{--                <th>Supplier</th>--}}
{{--                <th>Exporter</th>--}}
{{--                <th>Order Date</th>--}}
{{--                <th>Total Amount</th>--}}
{{--                <th>Status</th>--}}
{{--                <th>Action</th>--}}
{{--            </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--            @foreach($purchaseOrders as $po)--}}
{{--                <tr>--}}
{{--                    <td>PO-{{ $po->id }}</td>--}}
{{--                    <td>{{ $po->supplier->name ?? '-' }}</td>--}}
{{--                    <td>{{ $po->exporter->name ?? '-' }}</td>--}}
{{--                    <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M, Y') }}</td>--}}
{{--                    <td>{{ number_format($po->total_amount, 2) }}</td>--}}
{{--                    <td>{{ ucfirst($po->status) }}</td>--}}
{{--                    <td>--}}
{{--                        <a href="{{ route('purchase.order.show', $po->id) }}" class="btn btn-sm btn-primary">View</a>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}
{{--@endsection--}}
