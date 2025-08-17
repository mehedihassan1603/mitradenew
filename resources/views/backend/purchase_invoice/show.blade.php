@extends('backend.layout.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Purchase Invoice Details</h2>
        <div>
            <a href="{{ route('purchase.invoice.index') }}" class="btn btn-secondary">Back to List</a>
            <button onclick="printInvoice()" class="btn btn-primary">Print</button>
        </div>
    </div>

    <div id="invoice-print-area">
        {{-- Invoice Info --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">Invoice Information</div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4"><strong>PI Number:</strong> {{ $invoice->pi_number }}</div>
                    <div class="col-md-4"><strong>PI Date:</strong> {{ \Carbon\Carbon::parse($invoice->pi_date)->format('d M, Y') }}</div>
                    <div class="col-md-4"><strong>Notes:</strong> {{ $invoice->notes ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Supplier / Exporter Info --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Supplier & Exporter</div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Supplier:</strong> {{ $invoice->order->supplier->name ?? '-' }}</div>
                    <div class="col-md-6"><strong>Exporter:</strong> {{ $invoice->order->exporter->name ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Invoice Items</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->order->items as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Transportation Cost</th>
                            <th>{{ number_format($invoice->transportation_cost, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Grand Total</th>
                            <th>{{ number_format($invoice->total_amount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Attachments --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Attachments</div>
            <div class="card-body">
                @if($invoice->attachments->count() > 0)
                    <ul>
                        @foreach($invoice->attachments as $file)
                            <li>
                                <a href="{{ asset($file->file_path) }}" target="_blank">
                                    {{ basename($file->file_path) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No attachments uploaded.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function printInvoice() {
        var printContents = document.getElementById('invoice-print-area').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // refresh page after print
    }
</script>
@endpush
