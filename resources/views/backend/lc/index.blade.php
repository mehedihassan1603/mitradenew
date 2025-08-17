@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>LC Management</h2>
        <a href="{{ route('lcs.create') }}" class="btn btn-primary">+ Issue New LC</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>LC No</th>
                <th>Type</th>
                <th>Supplier</th>
                <th>Exporter</th>
                <th>Bank</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Expiry</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lcs as $lc)
                <tr>
                    <td>{{ $lc->lc_number }}</td>
                    <td>{{ $lc->lc_type }}</td>
                    <td>{{ $lc->supplier->name ?? '-' }}</td>
                    <td>{{ $lc->exporter->name ?? '-' }}</td>
                    <td>{{ $lc->bank->name ?? '-' }}</td>
                    <td>${{ number_format($lc->lc_amount, 2) }}</td>
                    <td>
                        <span class="badge
                            @if($lc->status=='Pending') bg-warning
                            @elseif($lc->status=='Approved') bg-success
                            @else bg-secondary @endif">
                            {{ $lc->status }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($lc->expiry_date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('lc.show', $lc->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('lcs.edit', $lc->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('lcs.destroy', $lc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No LC records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
