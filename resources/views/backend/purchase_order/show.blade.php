@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Purchase Order #PO-{{ $po->id }}</h2>

    <p><strong>Supplier:</strong> {{ $po->supplier->name ?? '-' }}</p>
    <p><strong>Exporter:</strong> {{ $po->exporter->name ?? '-' }}</p>
    <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($po->order_date)->format('d M, Y') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($po->status) }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Grand Total:</th>
                <th>{{ number_format($po->total_amount, 2) }}</th>
            </tr>
        </tfoot>
    </table>

     <a href="{{ route('purchase.invoice.create', $po->id) }}" class="btn btn-success mb-3">
        Create Purchase Invoice
    </a>
</div>
@endsection
