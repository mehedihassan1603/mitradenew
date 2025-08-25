@extends('backend.layout.main')

@section('content')
<div class="container-fluid">
    <h2>Received Invoice Details</h2>

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Invoice Info</div>
        <div class="card-body">
            <p><strong>PI Number:</strong> {{ $received->invoice->pi_number }}</p>
            <p><strong>BL Number:</strong> {{ $received->bl_number }}</p>
            <p><strong>Supplier:</strong> {{ $received->invoice->order->supplier->name ?? '-' }}</p>
            <p><strong>Exporter:</strong> {{ $received->invoice->order->exporter->name ?? '-' }}</p>
            <p><strong>Notes:</strong> {{ $received->received_notes ?? '-' }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-success text-white">Received Items</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
    <thead>
    <tr>
        <th>Product</th>
        <th>Received Qty</th>
        <th>Unit Price</th>
        <th>Base + HS VAT</th>
        <th>Allocated Charges</th>
        <th>Actual Landed Cost</th>
    </tr>
</thead>
<tbody>
    @php
        $totalCharges = ($received->transportation_cost ?? 0) + ($received->charges->sum('amount') ?? 0);
        $totalItemsCost = $received->items->sum('unit_price_with_vat');
    @endphp
    @foreach($received->items as $item)
        @php
            $baseWithVat = $item->unit_price_with_vat; // already stored when saving
            $allocatedCharge = ($totalItemsCost > 0)
                ? ($baseWithVat / $totalItemsCost) * $totalCharges
                : 0;
            $actualCost = $baseWithVat + $allocatedCharge;
        @endphp
        <tr>
            <td>{{ $item->product->name ?? '-' }}</td>
            <td>{{ $item->received_qty }}</td>
            <td>{{ number_format($item->unit_price,2) }}</td>
            <td>{{ number_format($baseWithVat,2) }}</td>
            <td>{{ number_format($allocatedCharge,2) }}</td>
            <td>{{ number_format($actualCost,2) }}</td>
        </tr>
    @endforeach
</tbody>

</table>

<div class="mt-3">
    <p><strong>Total Base + VAT:</strong> {{ number_format($totalItemsCost,2) }}</p>
    <p><strong>Total Additional Charges:</strong> {{ number_format($totalCharges,2) }}</p>
    <p><strong>Grand Total (Landed Cost):</strong> {{ number_format($totalItemsCost + $totalCharges,2) }}</p>
</div>


        </div>
    </div>

    @if($received->status == 0)
        <div class="card">
            <div class="card-header bg-warning">Approve and Add to Warehouse</div>
            <div class="card-body">
                <form action="{{ route('purchase.invoice.received.approve', $received->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Select Warehouse</label>
                        <select name="warehouse_id" class="form-select" required>
                            <option value="">-- Select Warehouse --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-success mt-3">Already Approved</div>
    @endif
</div>
@endsection
