@extends('backend.layout.main')

@section('content')
    <div class="container">
        <div class="container-fluid">
            <h2>Purchase Invoice Received</h2>

            <form action="{{ route('purchase.invoice.received.store') }}" method="POST">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                {{-- Invoice Info --}}
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">Invoice Details</div>
                    <div class="card mb-3">
                        <div class="card-body p-0">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th>PI Number</th>
                                        <td>{{ $invoice->pi_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>PI Date</th>
                                        <td>{{ \Carbon\Carbon::parse($invoice->pi_date)->format('d M, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Notes</th>
                                        <td>{{ $invoice->notes ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Supplier</th>
                                        <td>{{ $invoice->order->supplier->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Exporter</th>
                                        <td>{{ $invoice->order->exporter->name ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">Invoice Items</div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 0%">#</th>
                                        <th style="width: 26%">Product</th>
                                        <th style="width: 3%">HS Code</th>
                                        <th style="width: 1%">Qty</th>
                                        <th style="width: 4%">Unit Price</th>
                                        <th style="width: 1%">VAT</th>
                                        <th style="width: 7%">Total (with VAT)</th>
                                        <th style="width: 4%">Received Qty</th> {{-- Extra input field --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->product->name ?? 'N/A' }} <br><strong>(Code-{{$item->product->code  }})</strong></td>
                                            <td>{{ $item->product->hs->name ?? '' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->product->hs->value ?? '' }}</td>
                                            <td>
                                                {{ number_format(
                                                    $item->line_total + ($item->line_total * ($item->product->hs->value ?? 0) / 100),
                                                    2
                                                ) }}
                                            </td>

                                            <td>
                                                <input type="number" name="received_qty[{{ $item->id }}]" value="{{ $item->quantity }}" min="0" class="form-control">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- BL Number --}}
<div class="row mb-3">
    <div class="col-md-6">
        <label for="bl_number" class="form-label">BL Number</label>
        <input type="text" name="bl_number" id="bl_number" class="form-control" required>
    </div>

    {{-- LC Dropdown --}}
    <div class="col-md-6">
        <label for="lc_id" class="form-label">LC Number</label>
        <select name="lc_id" id="lc_id" class="form-control" required>
            <option value="">Select LC</option>
            @foreach($lcs as $lc)
                <option value="{{ $lc->id }}" data-amount="{{ $lc->lc_amount }}">{{ $lc->lc_number }}</option>
            @endforeach
        </select>
        <input type="number" id="lc_amount" class="form-control mt-2" placeholder="LC Amount" readonly>
    </div>
</div>

                            {{-- Extra Input Fields --}}
                            <div class="row mt-3">
                                <div class="col-md-4 mb-2">
                                    <label for="transportation_cost" class="form-label">Transportation Cost</label>
                                    <input type="number" name="transportation_cost" id="transportation_cost" value="{{ number_format($invoice->transportation_cost, 0) }}" class="form-control">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- Extra Notes --}}
                <div class="mb-3">
                    <label for="received_notes" class="form-label">Received Notes</label>
                    <textarea name="received_notes" id="received_notes" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit Received</button>
            </form>
        </div>
    </div>

/div>
    </div>
