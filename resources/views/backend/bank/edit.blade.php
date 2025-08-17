@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <h2>Edit Bank - {{ $bank->name }}</h2>

    <form action="{{ route('banks.update', $bank->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Bank Name</label>
            <input type="text" name="name" value="{{ $bank->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Branch</label>
            <input type="text" name="branch" value="{{ $bank->branch }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Swift Code</label>
            <input type="text" name="swift_code" value="{{ $bank->swift_code }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Contact Person</label>
            <input type="text" name="contact_person" value="{{ $bank->contact_person }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $bank->phone }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $bank->email }}" class="form-control">
        </div>

        <button class="btn btn-success">Update</button>
    </form>
</div>
@endsection
