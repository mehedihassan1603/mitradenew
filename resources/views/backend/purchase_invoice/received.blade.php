@extends('backend.layout.main')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Purchase Invoice Received</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('purchase.invoice.received.store') }}" method="POST">
        @csrf
        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

        {{-- ================= INVOICE DETAILS ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Invoice Details</div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th>PI Number</th>
                            <td>{{ $invoice->pi_number }}</td>
                            <th>PI Date</th>
                            <td>{{ \Carbon\Carbon::parse($invoice->pi_date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>{{ $invoice->order->supplier->name ?? '-' }}</td>
                            <th>Exporter</th>
                            <td>{{ $invoice->order->exporter->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td colspan="3">{{ $invoice->notes ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= INVOICE ITEMS ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Invoice Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>HS Code</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>VAT (%)</th>
                                <th>Total (with VAT)</th>
                                <th>Received Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $item->product->name ?? 'N/A' }} <br>
                                        <strong>(Code: {{ $item->product->code }})</strong>
                                    </td>
                                    <td>{{ $item->product->hs->name ?? '' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->product->hs->value ?? '' }}</td>
                                    <td>
                                        {{ number_format($item->line_total + ($item->line_total * ($item->product->hs->value ?? 0) / 100), 2) }}
                                    </td>
                                    <td>
                                        <input type="number" name="received_qty[{{ $item->id }}]"
                                               value="{{ $item->quantity }}" min="0"
                                               class="form-control form-control-sm">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ================= BL & LC DETAILS ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">BL & LC Details</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bl_number" class="form-label">BL Number <span class="text-danger">*</span></label>
                        <input type="text" name="bl_number" id="bl_number" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lc_id" class="form-label">LC Number <span class="text-danger">*</span></label>
                        <select name="lc_id" id="lc_id" class="form-control" required>
                            <option value="">Select LC</option>
                            @foreach($lcs as $lc)
                                <option value="{{ $lc->id }}" data-amount="{{ $lc->lc_amount }}">
                                    {{ $lc->lc_number }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" id="lc_amount" class="form-control mt-2" placeholder="LC Amount" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= ADDITIONAL COSTS ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">Additional Costs</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="transportation_cost" class="form-label">Transportation Cost</label>
                        <input type="number" name="transportation_cost" id="transportation_cost"
                               value="{{ $invoice->transportation_cost ?? 0 }}"
                               class="form-control">
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= ADDITIONAL CHARGES ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Additional Charges</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <select id="chargeSelect" class="form-control">
                            <option value="">-- Select Charge --</option>
                            @foreach($allcharges as $charge)
                                <option value="{{ $charge->id }}">{{ $charge->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="button" id="addChargeBtn" class="btn btn-primary">Add Charge</button>
                    </div>
                </div>

                <table class="table table-bordered" id="chargesTable">
                    <thead>
                        <tr>
                            <th>Charge</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        {{-- ================= NOTES ================= --}}
        <div class="mb-3">
            <label for="received_notes" class="form-label">Received Notes</label>
            <textarea name="received_notes" id="received_notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Submit Received</button>
    </form>
</div>

{{-- SCRIPT FOR LC AMOUNT & CHARGES --}}
<script>
    document.getElementById('lc_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        document.getElementById('lc_amount').value = selected.dataset.amount || '';
    });

    document.getElementById('addChargeBtn').addEventListener('click', function() {
        let chargeSelect = document.getElementById('chargeSelect');
        let selectedId = chargeSelect.value;
        let selectedText = chargeSelect.options[chargeSelect.selectedIndex].text;

        if (selectedId && !document.querySelector(`#chargesTable input[value="${selectedId}"]`)) {
            let tableBody = document.querySelector('#chargesTable tbody');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${selectedText}<input type="hidden" name="charge_id[]" value="${selectedId}"></td>
                <td><input type="number" step="0.01" name="charge_amount[]" class="form-control" required></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button></td>
            `;
            tableBody.appendChild(newRow);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endsection
