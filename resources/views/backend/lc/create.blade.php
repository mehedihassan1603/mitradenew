@extends('backend.layout.main')

@section('content')
<div class="container">
    <h2>Create New LC</h2>

    <form action="{{ route('lcs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>LC Number</label>
            <input type="text" name="lc_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>LC Type</label>
            <select name="lc_type" class="form-control" required>
                <option value="Sight">Sight</option>
                <option value="Usance">Usance</option>
                <option value="Back-to-Back">Back-to-Back</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Application Date</label>
            <input type="date" name="application_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>Approval Date</label>
            <input type="date" name="approval_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control">
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Exporter</label>
            <select name="exporter_id" class="form-control">
                <option value="">-- Select Exporter --</option>
                @foreach($exporters as $exporter)
                <option value="{{ $exporter->id }}">{{ $exporter->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Bank</label>
            <select name="bank_id" class="form-control" required>
                @foreach($banks as $bank)
                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>LC Amount</label>
            <input type="number" step="0.01" name="lc_amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Issue Date</label>
            <input type="date" name="issue_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control"></textarea>
        </div>
        <hr>
        <h4>Upload Documents</h4>
        <div id="document-section">
            <div class="doc-row mb-2">
                <input type="text" name="document_name[]" placeholder="Document Name" class="form-control mb-1">
                <input type="file" name="documents[]" class="form-control">
            </div>
        </div>
        <button type="button" onclick="addDocumentRow()" class="btn btn-sm btn-secondary">Add More Document</button>

        <hr>
        <h4>LC Expenses</h4>
        <div id="expense-section">
            <div class="expense-row mb-2">
                <input type="text" name="expense_type[]" placeholder="Expense Type (e.g., Bank Charges)" class="form-control mb-1">
                <input type="number" step="0.01" name="expense_amount[]" placeholder="Amount" class="form-control">
            </div>
        </div>
        <button type="button" onclick="addExpenseRow()" class="btn btn-sm btn-secondary">Add More Expense</button>

        <hr>
        <button type="submit" class="btn btn-primary">Create LC</button>
    </form>
</div>

<script>
function addDocumentRow() {
    let html = `<div class="doc-row mb-2">
        <input type="text" name="document_name[]" placeholder="Document Name" class="form-control mb-1">
        <input type="file" name="documents[]" class="form-control">
    </div>`;
    document.getElementById('document-section').insertAdjacentHTML('beforeend', html);
}

function addExpenseRow() {
    let html = `<div class="expense-row mb-2">
        <input type="text" name="expense_type[]" placeholder="Expense Type (e.g., Bank Charges)" class="form-control mb-1">
        <input type="number" step="0.01" name="expense_amount[]" placeholder="Amount" class="form-control">
    </div>`;
    document.getElementById('expense-section').insertAdjacentHTML('beforeend', html);
}
</script>
@endsection
