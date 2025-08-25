@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Banks</h2>
        <a href="{{ route('banks.create') }}" class="btn btn-primary">+ Add Bank</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Branch</th>
                <th>Swift Code</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($banks as $bank)
            <tr>
                <td>{{ $bank->name }}</td>
                <td>{{ $bank->branch ?? '-' }}</td>
                <td>{{ $bank->swift_code ?? '-' }}</td>
                <td>{{ $bank->phone ?? '-' }}</td>
                <td>{{ $bank->email ?? '-' }}</td>
                <td>
                    <a href="{{ route('banks.edit', $bank->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('banks.destroy', $bank->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this bank?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">No banks found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
