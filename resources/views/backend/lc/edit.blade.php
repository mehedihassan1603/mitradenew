@extends('backend.layout.main')

@section('content')
<div class="container mt-4">
    <h2>Edit LC - {{ $lc->lc_number }}</h2>

    <form action="{{ route('lcs.update', $lc->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>LC Number</label>
            <input type="text" name="lc_number" value="{{ $lc->lc_number }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>LC Type</label>
            <select name="lc_type" class="form-control" required>
                <option value="Sight" {{ $lc->lc_type == 'Sight' ? 'selected' : '' }}>Sight</option>
                <option value="Usance" {{ $lc->lc_type == 'Usance' ? 'selected' : '' }}>Usance</option>
                <option value="Back-to-Back" {{ $lc->lc_type == 'Back-to-Back' ? 'selected' : '' }}>Back-to-Back</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control">
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $lc->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Exporter</label>
            <select name="exporter_id" class="form-control">
                <option value="">-- Select Exporter --</option>
                @foreach($exporters as $exporter)
                    <option value="{{ $exporter->id }}" {{ $lc->exporter_id == $exporter->id ? 'selected' : '' }}>
                        {{ $exporter->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Bank</label>
            <select name="bank_id" class="form-control" required>
                @foreach($banks as $bank)
                    <option value="{{ $bank->id }}" {{ $lc->bank_id == $bank->id ? 'selected' : '' }}>
                        {{ $bank->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>LC Amount</label>
            <input type="number" step="0.01" name="lc_amount" value="{{ $lc->lc_amount }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Issue Date</label>
            <input type="date" name="issue_date" value="{{ $lc->issue_date }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" value="{{ $lc->expiry_date }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Pending" {{ $lc->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ $lc->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Closed" {{ $lc->status == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control">{{ $lc->remarks }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update LC</button>
        <a href="{{ route('lcs.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
