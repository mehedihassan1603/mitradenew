@extends('backend.layout.main')

@section('content')
    <div class="container">
        <h2>Create Purchase Invoice for PO-{{ $po->id }}</h2>

        <form action="{{ route('purchase.invoice.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">
{{--            <div class="row">--}}
                <div class="mb-3">
                    <label>Purchase Invoice Date</label>
                    <input type="date" name="pi_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label>Purchase Invoice Number</label>
                    <div class="input-group">
                        <input type="text" id="pi_number" name="pi_number" class="form-control" required>
                        <button type="button" class="btn btn-outline-primary" onclick="generatePiNumber()">Generate</button>
                    </div>
                </div>
{{--            <div>--}}

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
                @foreach($po->items as $index => $item)
                    <tr>
                        <td>
                            {{ $item->product->name ?? '-' }}
                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                        </td>
                        <td>
                            <input type="number" name="items[{{ $index }}][quantity]"
                                   class="form-control qty-input"
                                   value="{{ $item->quantity }}"
                                   min="1" step="1"
                                   data-price="{{ $item->price }}">
                        </td>
                        <td>
                            <input type="hidden" name="items[{{ $index }}][price]" value="{{ $item->price }}">
                            {{ number_format($item->price,2) }}
                        </td>
                        <td class="line-total">{{ number_format($item->quantity * $item->price,2) }}</td>
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

            <div class="mb-3">
                <label>Transportation Cost</label>
                <input type="number" id="transportation_cost" name="transportation_cost" class="form-control" min="0" step="0.01" value="0">
            </div>

            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>


{{--            <div class="mb-3">--}}
{{--                <label>Attachments</label>--}}
{{--                <input type="file" name="attachments[]" class="form-control" multiple>--}}
{{--            </div>--}}

            <div class="mb-3">
                <label>Attachments</label>
                <div id="attachments-wrapper">
                    <div class="input-group mb-2">
                        <input type="file" name="attachments[]" class="form-control">
                        <button type="button" class="btn btn-danger remove-attachment d-none">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm mt-2" id="add-attachment">
                    + Add Attachment
                </button>
            </div>
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
        function recalcTotals() {
            let poTotal = 0;

            document.querySelectorAll('.qty-input').forEach(function(input) {
                let qty = parseFloat(input.value) || 0;
                let price = parseFloat(input.dataset.price) || 0;
                let lineTotal = qty * price;

                // update row total
                input.closest('tr').querySelector('.line-total').innerText = lineTotal.toFixed(2);

                poTotal += lineTotal;
            });

            document.getElementById("po_total").innerText = poTotal.toFixed(2);

            let transport = parseFloat(document.getElementById("transportation_cost").value) || 0;
            let grandTotal = poTotal + transport;
            document.getElementById("grand_total").innerText = grandTotal.toFixed(2);
        }

        document.querySelectorAll('.qty-input').forEach(function(input) {
            input.addEventListener("input", recalcTotals);
        });

        document.getElementById("transportation_cost").addEventListener("input", recalcTotals);



    // multiple file attachment create procedure
        document.getElementById('add-attachment').addEventListener('click', function () {
            let wrapper = document.getElementById('attachments-wrapper');

            let newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group', 'mb-2');

            newInputGroup.innerHTML = `
            <input type="file" name="attachments[]" class="form-control">
            <button type="button" class="btn btn-danger remove-attachment">Remove</button>
        `;

            wrapper.appendChild(newInputGroup);
        });

        // Event delegation for remove buttons
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-attachment')) {
                e.target.closest('.input-group').remove();
            }
        });


    </script>
@endsection
