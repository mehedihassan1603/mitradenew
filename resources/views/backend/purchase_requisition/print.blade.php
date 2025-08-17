@extends('backend.layout.main')

@section('content')
    <div class="container mt-5">
        <h2>Purchase Requisition Details</h2>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Date</th>
                <th>Ref Id</th>
                <th>Product</th>
                <th>Quantity</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $index => $productId)
                @php
                    $product = $productModels[$productId] ?? null;
                    $quantity = $quantities[$index] ?? 'N/A';
                @endphp
                <tr>
                    @if($index == 0 && count($products) > 0)
                        <td rowspan="{{ count($products) }}">{{ \Carbon\Carbon::parse($req->date)->format('d-M-Y') }}</td>
                        <td rowspan="{{ count($products) }}">{{ $req->ref_id }}</td>
                    @endif
                    <td>{{ $product ? $product->name : 'N/A' }}</td>
                    <td>{{ $quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>
@endsection
