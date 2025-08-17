@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Create Purchase Invoice for PO-{{ $po->id }}</h2>

    <form action="{{ route('purchase.invoice.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">

        <div class="mb-3">
            <label>PI Number</label>
            <div class="input-group">
                <input type="text" id="pi_number" name="pi_number" class="form-control" required>
                <button type="button" class="btn btn-outline-primary" onclick="generatePiNumber()">Generate</button>
            </div>
        </div>

        <div class="mb-3">
            <label>PI Date</label>
            <input type="date" name="pi_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label>Transportation Cost</label>
            <input type="number" id="transportation_cost" name="transportation_cost" class="form-control" min="0" step="0.01" value="0">
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Attachments</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
        </div>

        <h4>PO Items</h4>
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
                    <td>{{ number_format($item->price,2) }}</td>
                    <td>{{ number_format($item->quantity * $item->price,2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">PO Total:</th>
                    <th id="po_total">{{ number_format($po->total_amount,2) }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Grand Total (PO + Transport):</th>
                    <th id="grand_total">{{ number_format($po->total_amount,2) }}</th>
                </tr>
            </tfoot>
        </table>

        <button type="submit" class="btn btn-success">Create Purchase Invoice</button>
    </form>
</div>

<script>
    function generatePiNumber() {
        let now = new Date();
        let random = Math.floor(1000 + Math.random() * 9000); // 4 digit random
        let pi = "PI-" + now.getFullYear() + (now.getMonth()+1).toString().padStart(2,'0') +
                 now.getDate().toString().padStart(2,'0') + "-" + random;
        document.getElementById("pi_number").value = pi;
    }

    function updateGrandTotal() {
        let poTotal = parseFloat("{{ $po->total_amount }}");
        let transport = parseFloat(document.getElementById("transportation_cost").value) || 0;
        let grandTotal = poTotal + transport;
        document.getElementById("grand_total").innerText = grandTotal.toFixed(2);
    }

    document.getElementById("transportation_cost").addEventListener("input", updateGrandTotal);
</script>
@endsection
