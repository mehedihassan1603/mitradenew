@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <h2>Add New Exporter</h2>

    <form action="{{ route('exporters.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Exporter Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contact Person</label>
            <input type="text" name="contact_person" class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Save Exporter</button>
    </form>
</div>
@endsection
