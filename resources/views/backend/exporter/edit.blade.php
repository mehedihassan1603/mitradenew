@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <h2>Edit Exporter - {{ $exporter->name }}</h2>

    <form action="{{ route('exporters.update', $exporter->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Exporter Name</label>
            <input type="text" name="name" value="{{ $exporter->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contact Person</label>
            <input type="text" name="contact_person" value="{{ $exporter->contact_person }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $exporter->phone }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $exporter->email }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ $exporter->address }}</textarea>
        </div>

        <button class="btn btn-success">Update Exporter</button>
        <a href="{{ route('exporters.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
