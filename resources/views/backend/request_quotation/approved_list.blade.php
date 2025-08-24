@extends('backend.layout.main')

@section('content')

    {{--List form--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card" style="padding: 7px;">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h2 class="listH2">Approved Quotations</h2>
                        </div>
{{--                        <div class="align-items-center col">--}}
{{--                            <a href="#" class="float-right btn btn-primary">Create</a>--}}
{{--                        </div>--}}
                    </div>
                    <hr class="bg-secondary"/>
                    <div class="table-responsive">
                        <table class="table" id="requisitionTable">
                            <thead>
                            <tr>
                                <th>Requisition ID</th>
                                <th>Supplier</th>
                                <th>Total Price (BDT)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
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
