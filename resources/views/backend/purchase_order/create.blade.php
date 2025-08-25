@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Create Purchase Order</h2>

    <form action="{{ route('purchase.order.store') }}" method="POST">
        @csrf

        <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ trans('file.Date') }}</label>
                    <input type="date" name="date" id="date" class="form-control" />
                </div>
            </div>

            <div class="mb-3 col-md-6">
                <label>Requisition ID</label>
                <input type="text" name="requisition_id" class="form-control" value="{{ $requisition->ref_id }}" readonly>
                <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
            </div>

            <div class="mb-3 col-md-6">
                <div class="form-group">
                    <label>Purchase Order ID</label>
                    <div class="input-group">
                        <input type="text" name="po_id" id="po_id" class="form-control" required/>
                        <button type="button" class="btn btn-primary" id="generateBtn">Generate</button>
                    </div>
                </div>
            </div>


            <div class="mb-3 col-md-6">
                <label>Supplier</label>
                <input type="text" class="form-control" value="{{ $quotation->supplier->name }}" readonly>
                <input type="hidden" name="supplier_id" value="{{ $quotation->supplier_id }}">
            </div>

            <div class="mb-3 col-md-6">
                <label>Exporter</label>
                <select name="exporter_id" class="form-control" required>
                    <option value="">-- Select Exporter --</option>
                    @foreach($exporters as $exporter)
                        <option value="{{ $exporter->id }}">{{ $exporter->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
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
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
                <input type="hidden" name="product_ids[]" value="{{ $productId }}">
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-right">Grand Total</th>
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
            let qty   = parseFloat(row.querySelector('.qty-input')?.value) || 0;
            let price = parseFloat(row.querySelector('.price-input')?.value) || 0;
            let rowTotal = qty * price;

            if(row.querySelector('.row-total')){
                row.querySelector('.row-total').innerText = rowTotal.toFixed(2);
            }
            grandTotal += rowTotal;
        });
        document.getElementById('grand-total').innerText = grandTotal.toFixed(2);
    }

    // Qty change listener
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    // Row delete listener
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
            calculateTotals(); // Recalculate after removal
        }
    });

    // Initial calculation
    calculateTotals();
});



{{--  generate po_id method  --}}
let generatedNumbers = [];

function generateUniqueNumber() {
    let number;
    do {
        number = Math.floor(100000 + Math.random() * 900000); // 6-digit
    } while (generatedNumbers.includes(number)); // check duplicate

    generatedNumbers.push(number); // store number
    return number;
}

document.getElementById('generateBtn').addEventListener('click', function() {
    const uniqueNumber = generateUniqueNumber();
    document.getElementById('po_id').value = 'PO-' + uniqueNumber;
}); {{-- End generate po_id method --}}




</script>
@endpush

