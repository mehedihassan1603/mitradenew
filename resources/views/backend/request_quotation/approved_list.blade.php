@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Approved Quotations</h2>

{{--    @if(session('success'))--}}
{{--        <div class="alert alert-success">{{ session('success') }}</div>--}}
{{--    @endif--}}

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Requisition ID</th>
                <th>Supplier</th>
                <th>Total Price (BDT)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
{{--        <tbody>--}}
{{--            @foreach($approvedQuotations as $quote)--}}
{{--                <tr>--}}
{{--                    <td>{{ $quote->requisition->ref_id }}</td>--}}
{{--                    <td>{{ $quote->supplier->name }}</td>--}}
{{--                    <td>{{ $quote->supplier->name }}</td>--}}
{{--                    <td><span class="badge bg-success">Approved</span></td>--}}
{{--                    <td>--}}
{{--                        <a href="{{ route('purchase.order.create', [$quote->purchase_requisition_id, $quote->id]) }}"--}}
{{--                           class="btn btn-sm btn-primary">--}}
{{--                           Create PO--}}
{{--                        </a>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--        </tbody>--}}

        <tbody>
        @foreach($approvedQuotations as $quote)
            @php
                // decode prices
                $prices = json_decode($quote->prices, true) ?? [];

                // requisition quantities গুলো নিতে হবে
                $quantities = $quote->requisition->quantities ?? []; // ধরে নিচ্ছি requisition model এ quantities array আছে

                $totalPrice = 0;
                foreach ($quantities as $i => $qty) {
                    $totalPrice += ($prices[$i] ?? 0) * $qty;
                }
            @endphp
            <tr>
                <td>{{ $quote->requisition->ref_id }}</td>
                <td>{{ $quote->supplier->name }}</td>
                <td>{{ number_format($totalPrice, 2) }}</td>
                <td><span class="badge bg-success">Approved</span></td>
                <td>
                    <a href="{{ route('purchase.order.create', [$quote->purchase_requisition_id, $quote->id]) }}" class="btn btn-sm btn-primary">
                        Create PO
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
