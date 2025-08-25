@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <h2>Add New Bank</h2>

    <form action="{{ route('banks.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Bank Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Branch</label>
            <input type="text" name="branch" class="form-control">
        </div>

        <div class="mb-3">
            <label>Swift Code</label>
            <input type="text" name="swift_code" class="form-control">
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

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
