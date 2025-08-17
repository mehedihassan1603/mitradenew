@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Add Supplier Quote for Requisition #{{ $requisition->ref_id }}</h2>
    <form action="{{ route('request.quotation.store', $requisition->id) }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Supplier Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $i => $productId)
                    @php
                        $product = \App\Models\Product::find($productId);
                    @endphp
                    <tr>
                        <td>{{ $product->name ?? 'Unknown' }}</td>
                        <td>{{ $quantities[$i] ?? 0 }}</td>
                        <td>
                            <input type="number" step="0.01" name="prices[]" class="form-control" required>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Save Quotation</button>
    </form>
</div>
@endsection
