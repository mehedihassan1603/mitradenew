@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Purchase Orders</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>PO ID</th>
                <th>Requisition ID</th>
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
                    <td>{{ $po->requitition->ref_id ?? '-' }}</td>
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
@endsection
