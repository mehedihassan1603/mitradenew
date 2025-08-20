@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Approved Quotations</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Requisition ID</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvedQuotations as $quote)
                <tr>
                    <td>{{ $quote->purchase_requisition_id }}</td>
                    <td>{{ $quote->supplier->name }}</td>
                    <td><span class="badge bg-success">Approved</span></td>
                    <td>
                        <a href="{{ route('purchase.order.create', [$quote->purchase_requisition_id, $quote->id]) }}"
                           class="btn btn-sm btn-primary">
                           Create PO
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
