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
                                        <th width="30%">PI Number</th>
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
                                        <th width="30%">Supplier</th>
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
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Received Qty</th> {{-- Extra input field --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $key => $item)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $item->product->name ?? 'N/A' }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ number_format($item->price, 2) }}</td>
        <td>{{ number_format($item->line_total, 2) }}</td>
        <td>
            <input type="number" name="received_qty[{{ $item->id }}]"
                value="{{ $item->quantity }}" min="0" class="form-control">
        </td>
    </tr>
@endforeach

                                </tbody>
                            </table>

                            {{-- Extra Input Fields --}}
                            <div class="row mt-3">

                                <div class="col-md-4 mb-2">
    <label for="transportation_cost" class="form-label">Transportation Cost</label>
    <input type="number" name="transportation_cost" id="transportation_cost"
           value="{{ $invoice->transportation_cost }}" class="form-control" step="0.01">
</div>


                                <div class="col-md-4 mb-2">
                                    <label for="custom_duty" class="form-label">Custom Duty</label>
                                    <input type="number" name="custom_duty" id="custom_duty" class="form-control"
                                        step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="vat" class="form-label">VAT</label>
                                    <input type="number" name="vat" id="vat" class="form-control" step="0.01">
                                </div>


                                <div class="col-md-4 mb-2">
                                    <label for="supplementary_duty" class="form-label">Supplementary Duty</label>
                                    <input type="number" name="supplementary_duty" id="supplementary_duty"
                                        class="form-control" step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="thc" class="form-label">Terminal Handling Charges</label>
                                    <input type="number" name="thc" id="thc" class="form-control" step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="container_handling" class="form-label">Container Handling /
                                        Offloading</label>
                                    <input type="number" name="container_handling" id="container_handling"
                                        class="form-control" step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="custom_clearance" class="form-label">Custom Clearance Fees</label>
                                    <input type="number" name="custom_clearance" id="custom_clearance" class="form-control"
                                        step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="documentation_charges" class="form-label">Documentation Charges</label>
                                    <input type="number" name="documentation_charges" id="documentation_charges"
                                        class="form-control" step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="truck_cost" class="form-label">Truck / Carrier Cost</label>
                                    <input type="number" name="truck_cost" id="truck_cost" class="form-control"
                                        step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="warehouse_receiving" class="form-label">Warehouse Receiving Charges</label>
                                    <input type="number" name="warehouse_receiving" id="warehouse_receiving"
                                        class="form-control" step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="inspection_qc" class="form-label">Inspection / QC Charges</label>
                                    <input type="number" name="inspection_qc" id="inspection_qc" class="form-control"
                                        step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="packaging_labeling" class="form-label">Packaging / Labeling</label>
                                    <input type="number" name="packaging_labeling" id="packaging_labeling"
                                        class="form-control" step="0.01">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="fuel_toll" class="form-label">Fuel Surcharge</label>
                                    <input type="number" name="fuel_toll" id="fuel_toll" class="form-control"
                                        step="0.01">
                                </div>

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
@endsection
