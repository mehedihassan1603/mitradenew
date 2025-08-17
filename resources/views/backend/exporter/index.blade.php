@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Exporters</h2>
        <a href="{{ route('exporters.create') }}" class="btn btn-primary">+ Add Exporter</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($exporters as $exporter)
            <tr>
                <td>{{ $exporter->name }}</td>
                <td>{{ $exporter->contact_person ?? '-' }}</td>
                <td>{{ $exporter->phone ?? '-' }}</td>
                <td>{{ $exporter->email ?? '-' }}</td>
                <td>{{ $exporter->address ?? '-' }}</td>
                <td>
                    <a href="{{ route('exporters.edit', $exporter->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('exporters.destroy', $exporter->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this exporter?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">No exporters found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
