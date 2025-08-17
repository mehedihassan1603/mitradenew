@extends('backend.layout.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Purchase Invoices</h2>
        <a href="{{ route('purchase.order.index') }}" class="btn btn-primary">Create From PO</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>PI Number</th>
                        <th>PI Date</th>
                        <th>Supplier</th>
                        <th>Exporter</th>
                        <th>Total Amount</th>
                        <th>Transportation Cost</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $key => $invoice)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $invoice->pi_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->pi_date)->format('d M, Y') }}</td>
                            <td>{{ $invoice->order->supplier->name ?? '-' }}</td>
                            <td>{{ $invoice->order->exporter->name ?? '-' }}</td>
                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                            <td>{{ number_format($invoice->transportation_cost, 2) }}</td>
                            <td>
                                <a href="{{ route('purchase.invoice.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                                {{-- Future: Add Edit/Delete if needed --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No Purchase Invoices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
