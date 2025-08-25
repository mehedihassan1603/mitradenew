@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <h2>LC Details : {{ $lc->lc_number }}</h2>
    <a href="{{ route('lcs.index') }}" class="btn btn-secondary mb-3">⬅ Back</a>

    <div class="card mb-3">
        <div class="card-header">
            Basic Information
        </div>
        <div class="card-body">
            <p><strong>LC Type:</strong> {{ $lc->lc_type }}</p>
            <p><strong>Supplier:</strong> {{ $lc->supplier->name ?? '-' }}</p>
            <p><strong>Exporter:</strong> {{ $lc->exporter->name ?? '-' }}</p>
            <p><strong>Bank:</strong> {{ $lc->bank->name ?? '-' }}</p>
            <p><strong>Amount:</strong> TK. {{ number_format($lc->lc_amount, 2) }}</p>
            <p><strong>Status:</strong>
                <span class="badge
                    @if($lc->status=='Pending') bg-warning
                    @elseif($lc->status=='Approved') bg-success
                    @else bg-secondary @endif">
                    {{ $lc->status }}
                </span>
            </p>
            <p><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($lc->issue_date)->format('d M Y') }}</p>
            <p><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($lc->expiry_date)->format('d M Y') }}</p>
            <p><strong>Remarks:</strong> {{ $lc->remarks ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            Uploaded Documents
        </div>
        <div class="card-body">
            @if($lc->documents->count() > 0)
                <ul>
                    @foreach($lc->documents as $index => $doc)
                        <li>
                            {{ $doc->document_type }} : <a href="{{ asset($doc->file_path) }}" target="_blank">View</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No documents uploaded.</p>
            @endif
        </div>
    </div>
</div>
@endsection
