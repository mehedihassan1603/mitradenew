@extends('ecommerce::frontend.layout.main')

@section('title') {{ $ecommerce_setting->site_title ?? '' }} @endsection
@section('description') @endsection

@section('content')
    <!-- Section starts -->
    <section class="">
        <div class="col-md-6 offset-md-3 text-center mb-5">
            <div style="font-size: 60px; color: forestgreen;">
                <span class="material-symbols-outlined">verified</span>
            </div>
            <h3 class="mt-3">{{ trans('file.Thank you for your order') }}</h3>
            <p class="lead">
                {{ trans('file.Here is your order reference no') }} -
                <span class="theme-color">{{ $sale_reference }}</span>.
                {{ trans('file.You will receive an email with delivery details shortly') }}
            </p>
        </div>
    </section>
    <!-- Section ends -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GTM-TWGBLHDD"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GTM-TWGBLHDD'); // Replace with your GA4 Measurement ID
    </script>

    @if (isset($sale))
    <script>
        gtag("event", "purchase", {
            transaction_id: "{{ $sale->reference_no }}",
            value: {{ $sale->grand_total }},
            tax: {{ $sale->total_tax ?? 0 }},
            shipping: {{ $sale->shipping_cost ?? 0 }},
            currency: "BDT", 
        });
    </script>
    @endif
@endsection
