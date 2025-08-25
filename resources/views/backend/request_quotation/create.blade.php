@extends('backend.layout.main')

@section('content')
    <div class="container">
        <h2>Add Supplier Quote for Requisition #{{ $requisition->ref_id }}</h2>
        <form action="{{ route('request.quotation.store', $requisition->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="form-group col-md-6 mb-3">
                    <label for="supplier_id">Supplier</label>
                    <select name="supplier_id" class="form-control" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('file.Date') }}</label>
                        <input type="date" name="date" id="date" class="form-control" />
                    </div>
                </div>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $i => $productId)
                    @php
                        $product = \App\Models\Product::find($productId);
                    @endphp
                    <tr>
                        <td>{{ $product->name ?? 'Unknown' }} <strong>( {{ $product->code }} )</strong></td>
                        <td class="quantity">{{ $quantities[$i] ?? 0 }}</td>
                        <td>
                            <input type="number" step="0.01" name="prices[]" class="form-control unit-price" required>
                        </td>
                        <td class="total-price">0.00</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save Quotation</button>
        </form>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('table tbody tr');

            rows.forEach(row => {
                const unitPriceInput = row.querySelector('.unit-price');
                const quantity = parseFloat(row.querySelector('.quantity').textContent) || 0;
                const totalPriceCell = row.querySelector('.total-price');

                unitPriceInput.addEventListener('input', function() {
                    const unitPrice = parseFloat(this.value) || 0;
                    const total = unitPrice * quantity;
                    totalPriceCell.textContent = total.toFixed(2);
                });
            });
        });

    </script>





@endsection
