{{--@extends('backend.layout.main')--}}

{{--<style>--}}
{{--    table.table.table-bordered.tableHead thead th {--}}
{{--        width: 0%;--}}
{{--    }--}}
{{--</style>--}}

{{--@section('content')--}}
{{--<div class="container">--}}
{{--    <div class="d-flex justify-content-between align-items-center mb-3">--}}
{{--        <h2>Quotation Comparison for Requisition #{{ $requisition->ref_id }}</h2>--}}
{{--        <button class="btn btn-primary" onclick="printComparison()">Print</button>--}}
{{--    </div>--}}

{{--    <div id="comparisonTableWrapper">--}}
{{--        <table class="table table-bordered tableHead">--}}
{{--            <thead>--}}
{{--                <tr>--}}
{{--                    <th>Product</th>--}}
{{--                    <th>Quantity</th>--}}
{{--                    @foreach($quotations as $quote)--}}
{{--                        <th>{{ $quote->supplier->name }} <br> <strong>({{ $quote->date ? \Carbon\Carbon::parse($quote->date)->format('d M-Y') : 'Date' }})</strong></th>--}}
{{--                    @endforeach--}}
{{--                </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--                @foreach($products as $i => $productId)--}}
{{--                    @php--}}
{{--                        $rowPrices = [];--}}
{{--                        foreach($quotations as $quote){--}}
{{--                            $prices = json_decode($quote->prices, true) ?? [];--}}
{{--                            $rowPrices[] = $prices[$i] ?? null;--}}
{{--                        }--}}
{{--                        $minPrice = collect($rowPrices)->filter()->min(); // lowest valid price--}}
{{--                    @endphp--}}
{{--                    <tr>--}}
{{--                        <td>{{ $productModels[$productId]->name ?? 'Unknown' }}</td>--}}
{{--                        <td>{{ $quantities[$i] ?? 0 }}</td>--}}
{{--                        @foreach($quotations as $qIndex => $quote)--}}
{{--                            @php--}}
{{--                                $prices = json_decode($quote->prices, true) ?? [];--}}
{{--                                $price = $prices[$i] ?? null;--}}
{{--                            @endphp--}}
{{--                            <td @if($price !== null && $price == $minPrice) style="background: #d4edda; font-weight:bold;" @endif>--}}
{{--                                {{ $price !== null ? number_format($price, 2) : '-' }}--}}
{{--                            </td>--}}
{{--                        @endforeach--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--            </tbody>--}}
{{--            <tfoot>--}}
{{--                @php--}}
{{--                    $totals = [];--}}
{{--                    foreach($quotations as $quote){--}}
{{--                        $prices = json_decode($quote->prices, true) ?? [];--}}
{{--                        $total = 0;--}}
{{--                        foreach($quantities as $idx => $qty){--}}
{{--                            $total += ($prices[$idx] ?? 0) * $qty;--}}
{{--                        }--}}
{{--                        $totals[$quote->id] = $total;--}}
{{--                    }--}}
{{--                    $minTotal = collect($totals)->min();--}}
{{--                @endphp--}}
{{--                <tr>--}}
{{--                    <th colspan="2">Total</th>--}}
{{--                    @foreach($quotations as $quote)--}}
{{--                        @php $total = $totals[$quote->id]; @endphp--}}
{{--                        <th @if($total == $minTotal) style="background: #c3e6cb; font-weight:bold;" @endif>--}}
{{--                            {{ number_format($total, 2) }}--}}

{{--                            --}}{{-- Approve button --}}
{{--                            <form action="{{ route('request.quotation.approve', [$requisition->id, $quote->id]) }}" method="GET" class="mt-2">--}}
{{--                                <button type="submit" class="btn btn-sm btn-success">--}}
{{--                                    Approve--}}
{{--                                </button>--}}
{{--                            </form>--}}

{{--                        </th>--}}
{{--                    @endforeach--}}
{{--                </tr>--}}
{{--            </tfoot>--}}
{{--        </table>--}}
{{--    </div>--}}
{{--</div>--}}
{{--@endsection--}}

{{--@push('scripts')--}}
{{--<script>--}}
{{--    function printComparison() {--}}
{{--        let divContents = document.getElementById("comparisonTableWrapper").innerHTML;--}}
{{--        let printWindow = window.open('', '', 'height=600,width=900');--}}
{{--        printWindow.document.write('<html><head><title>Quotation Comparison</title>');--}}
{{--        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">');--}}
{{--        printWindow.document.write('</head><body>');--}}
{{--        printWindow.document.write(divContents);--}}
{{--        printWindow.document.write('</body></html>');--}}
{{--        printWindow.document.close();--}}
{{--        printWindow.print();--}}
{{--    }--}}
{{--</script>--}}
{{--@endpush--}}


@extends('backend.layout.main')

<style>

    table.table.table-bordered.tableHead thead th {
        text-align: center;
        vertical-align: middle;
    }
    button.btn.btn-sm.btn-success.approveBtn:hover {
        background: #098565 !important;
        color: #fff;
    }



</style>

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Quotation Comparison for Requisition #{{ $requisition->ref_id }}</h2>
            <button class="btn btn-primary" onclick="printComparison()">Print</button>
        </div>

        <div id="comparisonTableWrapper">
            <table class="table table-bordered tableHead">
                <thead>
                <tr>
                    <th rowspan="2">Product</th>
                    <th rowspan="2">Quantity</th>
                    @foreach($quotations as $quote)
                        <th colspan="2">
                            {{ $quote->supplier->name }} <br>
                            <strong>({{ $quote->date ? \Carbon\Carbon::parse($quote->date)->format('d M-Y') : 'Date' }})</strong>
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($quotations as $quote)
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($products as $i => $productId)
                    @php
                        $rowUnitPrices = [];
                        $rowTotals = [];
                        foreach($quotations as $quote){
                            $prices = json_decode($quote->prices, true) ?? [];
                            $unitPrice = $prices[$i] ?? null;
                            $total = $unitPrice ? $unitPrice * ($quantities[$i] ?? 0) : null;
                            $rowUnitPrices[] = $unitPrice;
                            $rowTotals[] = $total;
                        }
                        $minPrice = collect($rowUnitPrices)->filter()->min();
                        $minTotal = collect($rowTotals)->filter()->min();
                    @endphp
                    <tr>
                        <td>{{ $productModels[$productId]->name ?? 'Unknown' }}</td>
                        <td>{{ $quantities[$i] ?? 0 }}</td>
                        @foreach($quotations as $qIndex => $quote)
                            @php
                                $prices = json_decode($quote->prices, true) ?? [];
                                $unitPrice = $prices[$i] ?? null;
                                $total = $unitPrice ? $unitPrice * ($quantities[$i] ?? 0) : null;
                            @endphp
                            <td @if($unitPrice !== null && $unitPrice == $minPrice) style="background: #d4edda; font-weight:bold;" @endif>
                                {{ $unitPrice !== null ? number_format($unitPrice, 2) : '-' }}
                            </td>
                            <td @if($total !== null && $total == $minTotal) style="background: #c3e6cb; font-weight:bold;" @endif>
                                {{ $total !== null ? number_format($total, 2) : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                @php
                    $totals = [];
                    foreach($quotations as $quote){
                        $prices = json_decode($quote->prices, true) ?? [];
                        $total = 0;
                        foreach($quantities as $idx => $qty){
                            $total += ($prices[$idx] ?? 0) * $qty;
                        }
                        $totals[$quote->id] = $total;
                    }
                    $minTotal = collect($totals)->min();
                @endphp
                <tr>
                    <th colspan="2">Grand Total</th>
                    @foreach($quotations as $quote)
                        @php $total = $totals[$quote->id]; @endphp
                        <th colspan="2"
                            @if($total == $minTotal)
                                style="background: #c3e6cb; font-weight:bold;"
                            @endif
                        >
                            <div class="d-flex justify-content-between align-items-center">
                                {{-- Approve button left side --}}
                                <form action="{{ route('request.quotation.approve', [$requisition->id, $quote->id]) }}" method="GET" style="margin-bottom: 0px;">
                                    <button type="submit" class="btn btn-sm btn-success approveBtn">
                                        Approve
                                    </button>
                                </form>

                                {{-- Price right side --}}
                                <span> = {{ number_format($total, 2) }}</span>
                            </div>
                        </th>
                    @endforeach
                </tr>


                {{--                <tr>--}}
{{--                    <th colspan="2">Grand Total</th>--}}
{{--                    @foreach($quotations as $quote)--}}
{{--                        @php $total = $totals[$quote->id]; @endphp--}}
{{--                        <th colspan="2" @if($total == $minTotal) style="background: #c3e6cb; font-weight:bold;" @endif>--}}
{{--                            {{ number_format($total, 2) }}--}}

{{--                            --}}{{-- Approve button --}}
{{--                            <form action="{{ route('request.quotation.approve', [$requisition->id, $quote->id]) }}" method="GET" class="mt-2">--}}
{{--                                <button type="submit" class="btn btn-sm btn-success">--}}
{{--                                    Approve--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        </th>--}}
{{--                    @endforeach--}}
{{--                </tr>--}}
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function printComparison() {
            let divContents = document.getElementById("comparisonTableWrapper").innerHTML;
            let printWindow = window.open('', '', 'height=600,width=900');
            printWindow.document.write('<html><head><title>Quotation Comparison</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endpush
