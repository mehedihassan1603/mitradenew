@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Create Purchase Order</h2>

    <form action="{{ route('purchase.order.store') }}" method="POST">
        @csrf
        <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
        <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">

        <div class="mb-3">
            <label>Supplier</label>
            <input type="text" class="form-control" value="{{ $quotation->supplier->name }}" readonly>
            <input type="hidden" name="supplier_id" value="{{ $quotation->supplier_id }}">
        </div>

        <div class="mb-3">
            <label>Exporter</label>
            <select name="exporter_id" class="form-control" required>
                <option value="">-- Select Exporter --</option>
                @foreach($exporters as $exporter)
                    <option value="{{ $exporter->id }}">{{ $exporter->name }}</option>
                @endforeach
            </select>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            @php
    $grandTotal = 0;
@endphp

<tbody>
    @php $grandTotal = 0; @endphp
    @foreach($products as $i => $productId)
        @php
            $qty = $quantities[$i] ?? 0;
            $price = $prices[$i] ?? 0;
            $rowTotal = $qty * $price;
            $grandTotal += $rowTotal;
        @endphp
        <tr>
            <td>{{ $productModels[$productId]->name ?? 'Unknown' }}</td>
            <td>
                <input type="number" name="quantities[]"
                       class="form-control qty-input"
                       value="{{ $qty }}" min="1">
            </td>
            <td>
                <input type="text" name="prices[]"
                       class="form-control price-input"
                       value="{{ $price }}" readonly>
            </td>
            <td class="row-total">{{ $rowTotal }}</td>
            <input type="hidden" name="product_ids[]" value="{{ $productId }}">
        </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <th colspan="3" class="text-end">Grand Total:</th>
        <th id="grand-total">{{ $grandTotal }}</th>
    </tr>
</tfoot>



        </table>

        <button type="submit" class="btn btn-success">Submit Purchase Order</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            let qty   = parseFloat(row.querySelector('.qty-input').value) || 0;
            let price = parseFloat(row.querySelector('.price-input').value) || 0;
            let rowTotal = qty * price;

            row.querySelector('.row-total').innerText = rowTotal.toFixed(2);
            grandTotal += rowTotal;
        });
        document.getElementById('grand-total').innerText = grandTotal.toFixed(2);
    }

    // Listen for qty changes
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    // Run once at page load
    calculateTotals();
});
</script>
@endpush

