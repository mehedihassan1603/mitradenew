@extends('backend.layout.main')

@section('content')

    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card" style="padding: 7px;">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h2 class="listH2">Purchase Invoices</h2>
                        </div>
                        {{-- <div class="align-items-center col">--}}
                        {{--     <a href="#" class="float-right btn btn-primary">Create</a>--}}
                        {{-- </div>--}}
                    </div>
                    <hr class="bg-secondary"/>
                    <div class="table-responsive">
                        <table class="table" id="requisitionTable">
                            <thead>
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
        </div>
    </div>
    {{--End list form--}}


@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#requisitionTable').DataTable({
                paging: true,
                searching: true,
                ordering:  true
            });
        });
    </script>

@endpush
