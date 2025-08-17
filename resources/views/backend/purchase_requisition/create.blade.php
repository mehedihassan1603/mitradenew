@extends('backend.layout.main')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-4">Add Purchase Requisition</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('purchase.requisition.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('file.Date') }}</label>
                                <input type="date" name="date" id="date" class="form-control" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reference Id</label>
                                <div class="input-group">
                                    <input type="text" name="ref_id" id="ref_id" class="form-control" readonly />
                                    <button type="button" class="btn btn-primary" id="generateBtn">Generate</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4 col-md-12">
                        <label for="product_name" class="col-sm-3 col-form-label">Select Product</label>
                        <select class="form-control" multiple name="product_name[]" id="product_name">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-code="{{ $product->code }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <h4>Product Table List</h4>
                    <table class="table table-bordered mt-4" id="productTable">
                        <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary w-md mt-4">Create Purchase Requisition</button>
                </form>

            </div>
        </div>
    </div>
@endsection


@push('scripts')

    {{--  bootstrap css and js  --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{--  data table css and js  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <script>
        let productQuantities = {}; // প্রতিটি product এর quantity save
        let selectedProducts = {}; // table এ add হওয়া products track

        document.getElementById('product_name').addEventListener('change', function () {
            let selectedOptions = Array.from(this.options).filter(opt => opt.selected);

            // 1️⃣ নতুন select হওয়া products যোগ করো
            selectedOptions.forEach(option => {
                let productId = option.value;

                if (!selectedProducts[productId]) {
                    let name = option.text;
                    let code = option.getAttribute('data-code');

                    let qty = 1;
                    productQuantities[productId] = qty;
                    selectedProducts[productId] = true;

                    let row = `<tr id="product-row-${productId}">
                    <td>${name}</td>
                    <td>${code}</td>
                    <td>
                        <input type="number" name="quantities[${productId}]" value="${qty}"
                               class="form-control quantity-input" min="1" data-product-id="${productId}">
                    </td>
                </tr>`;

                    document.querySelector("#productTable tbody").insertAdjacentHTML('beforeend', row);

                    // quantity change হলে update হবে
                    document.querySelector(`#product-row-${productId} .quantity-input`).addEventListener('input', function () {
                        let pid = this.getAttribute('data-product-id');
                        productQuantities[pid] = this.value;
                    });
                }
            });

            // 2️⃣ যেগুলো unselect হলো সেগুলো remove করো
            Array.from(this.options).forEach(option => {
                let productId = option.value;
                if (!option.selected && selectedProducts[productId]) {
                    // table থেকে remove
                    let row = document.getElementById(`product-row-${productId}`);
                    if (row) row.remove();

                    // tracking থেকে বাদ
                    delete selectedProducts[productId];
                    delete productQuantities[productId];
                }
            });
        });


        {{--  generate ref_id method  --}}
        let generatedNumbers = [];

        function generateUniqueNumber() {
            let number;
            do {
                number = Math.floor(100000 + Math.random() * 900000); // 6-digit
            } while (generatedNumbers.includes(number)); // check duplicate

            generatedNumbers.push(number); // store number
            return number;
        }

        document.getElementById('generateBtn').addEventListener('click', function() {
            const uniqueNumber = generateUniqueNumber();
            document.getElementById('ref_id').value = 'PR-' + uniqueNumber;
        }); {{-- End generate ref_id method --}}


    </script>
@endpush
