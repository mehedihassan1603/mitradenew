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
                            <input type="date" name="date" id="date" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Reference Id</label>
                            <div class="input-group">
                                <input type="text" name="ref_id" id="ref_id" class="form-control" readonly/>
                                <button type="button" class="btn btn-primary" id="generateBtn">Generate</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Autocomplete product search --}}
                <div class="form-group mb-4">
                    <label>Search Product (Code or Name)</label>
                    <input type="text" id="lims_productcodeSearch" class="form-control" placeholder="Type code or name">
                </div>

                <h4>Product Table List</h4>
                <table class="table table-bordered mt-4" id="productTable">
                    <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Code</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button type="submit" class="btn btn-primary w-md mt-4">Create Purchase Requisition</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- bootstrap & datatable css/js --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"/>

<script>
    // Build product array with and without variants
    <?php $productArray = []; ?>
    var lims_product_code = [
        @foreach($products as $p)
            @if(isset($p->variations) && $p->variations->count() > 0)
                @foreach($p->variations as $variant)
                    <?php
                        $productArray[] = htmlspecialchars($variant->item_code).'|'.
                                          preg_replace('/[\n\r]/', "<br>", htmlspecialchars($p->name.' - '.$variant->name)).'|'.
                                          $p->id.'|'.$variant->id;
                    ?>
                @endforeach
            @else
                <?php
                    $productArray[] = htmlspecialchars($p->code).'|'.
                                      preg_replace('/[\n\r]/', "<br>", htmlspecialchars($p->name)).'|'.
                                      $p->id.'|';
                ?>
            @endif
        @endforeach
        {!! '"'.implode('","',$productArray).'"' !!}
    ];

    var selectedProducts = {};

    // Autocomplete setup
    $('#lims_productcodeSearch').autocomplete({
        source: function (request, response) {
            var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
            response($.grep(lims_product_code, function (item) {
                return matcher.test(item);
            }));
        },
        response: function (event, ui) {
            if (ui.content.length === 1) {
                var data = ui.content[0].value;
                $(this).autocomplete("close");
                productSearch(data);
                $(this).val('');
            }
        },
        select: function (event, ui) {
            var data = ui.item.value;
            productSearch(data);
            $(this).val('');
            return false;
        }
    });

    function productSearch(data) {
        // Format: code|name|product_id|variant_id(optional)
        var array = data.split('|');
        var code = array[0];
        var name = array[1];
        var productId = array[2];
        var variantId = array[3] || '';

        var uniqueKey = variantId ? productId + '_' + variantId : productId;

        if (!selectedProducts[uniqueKey]) {
            selectedProducts[uniqueKey] = true;

            var row = `<tr id="product-row-${uniqueKey}">
                <td>${name}</td>
                <td>${code}</td>
                <td>
                    <input type="number" name="quantities[${uniqueKey}]" value="1" min="1" class="form-control quantity-input">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-product" data-key="${uniqueKey}">
                        <i class="fa fa-trash"></i> Remove
                    </button>
                </td>
            </tr>`;
            document.querySelector("#productTable tbody").insertAdjacentHTML('beforeend', row);
        }
    }
    // Delegate event listener for dynamic rows
document.querySelector("#productTable").addEventListener('click', function (e) {
    if (e.target.closest('.remove-product')) {
        var button = e.target.closest('.remove-product');
        var key = button.getAttribute('data-key');
        // Remove row
        document.getElementById(`product-row-${key}`).remove();
        // Remove from selectedProducts
        delete selectedProducts[key];
    }
});



    // Generate unique Ref ID
    let generatedNumbers = [];
    function generateUniqueNumber() {
        let number;
        do {
            number = Math.floor(100000 + Math.random() * 900000);
        } while (generatedNumbers.includes(number));
        generatedNumbers.push(number);
        return number;
    }
    document.getElementById('generateBtn').addEventListener('click', function () {
        const uniqueNumber = generateUniqueNumber();
        document.getElementById('ref_id').value = 'PR-' + uniqueNumber;
    });
</script>
@endpush
