@extends('backend.layout.main')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Add Adjustment')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'qty_adjustment.store', 'method' => 'post', 'files' => true, 'id' => 'adjustment-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Warehouse')}} *</label>
                                            <select required id="warehouse_id" name="warehouse_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                                                @foreach($lims_warehouse_list as $warehouse)
                                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Attach Document')}}</label>
                                            <input type="file" name="document" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label>{{trans('file.Select Product')}}</label>
                                        <div class="search-box input-group">
                                            <button type="button" class="btn btn-secondary btn-lg"><i class="fa fa-barcode"></i></button>
                                            <input type="text" name="product_code_name" id="lims_productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-12">
                                        <h5>{{trans('file.Order Table')}} *</h5>
                                        <div class="table-responsive mt-3">
                                            <table id="myTable" class="table table-hover order-list">
                                                <thead>
                                                    <tr>
                                                        <th>{{trans('file.name')}}</th>
                                                        <th>{{trans('file.Batch No')}}</th>
                                                        <th>{{trans('file.Code')}}</th>
                                                        <th>{{trans('file.Unit Cost')}}</th>
                                                        <th>{{trans('file.Quantity')}}</th>
                                                        <th>{{trans('file.action')}}</th>
                                                        <th><i class="dripicons-trash"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot class="tfoot active">
                                                    <th colspan="4">{{trans('file.Total')}}</th>
                                                    <th id="total-qty" colspan="2">0</th>
                                                    <th><i class="dripicons-trash"></i></th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="hidden" name="total_qty" />
                                            <input type="hidden" name="item" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{trans('file.Note')}}</label>
                                            <textarea rows="5" class="form-control" name="note"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('scripts')
<script type="text/javascript">
	$("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #adjustment-create-menu").addClass("active");
    // array data depend on warehouse
    var lims_product_array = [];
    var product_code = [];
    var product_name = [];
    var product_qty = [];
    var unit_cost = [];

	$('.selectpicker').selectpicker({
	    style: 'btn-link',
	});



	$('select[name="warehouse_id"]').on('change', function() {
	    var id = $(this).val();
	    $.get('getproduct/' + id, function(data) {
            console.log('getData',data);
	        lims_product_array = [];
	        product_code = data[0];
	        product_name = data[1];
	        product_qty = data[2];
	        unit_cost = data[3];
	        $.each(product_code, function(index) {
	            lims_product_array.push(product_code[index] + ' (' + product_name[index] + ')' + '|' + unit_cost[index]);
	        });
	    });
	});

	var lims_productcodeSearch = $('#lims_productcodeSearch');

	lims_productcodeSearch.autocomplete({
	    source: function(request, response) {
	        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
	        response($.grep(lims_product_array, function(item) {
	            return matcher.test(item);
	        }));
	    },
	    response: function(event, ui) {
	        if (ui.content.length == 1) {
	            var data = ui.content[0].value;
	            $(this).autocomplete( "close" );
	            productSearch(data);
	        };
	    },
	    select: function(event, ui) {
	        var data = ui.item.value;
	        productSearch(data);
	    }
	});

	$("#myTable").on('input', '.qty', function() {
	    rowindex = $(this).closest('tr').index();
	    checkQuantity($(this).val(), true);
	});

	$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
	    rowindex = $(this).closest('tr').index();
	    $(this).closest("tr").remove();
	    calculateTotal();
	});

	$(window).keydown(function(e){
	    if (e.which == 13) {
	        var $targ = $(e.target);
	        if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
	            var focusNext = false;
	            $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
	                if (this === e.target) {
	                    focusNext = true;
	                }
	                else if (focusNext){
	                    $(this).focus();
	                    return false;
	                }
	            });
	            return false;
	        }
	    }
	});

	$('#adjustment-form').on('submit',function(e){
	    var rownumber = $('table.order-list tbody tr:last').index();
	    if (rownumber < 0) {
	        alert("Please insert product to order table!")
	        e.preventDefault();
	    }
	});





    $("#myTable").on("change", ".batch-no1", function () {
        var row = $(this).closest('tr');
        var rowindex = row.index();
        console.log('rowindex', rowindex);

        var product_id = row.find('.product-id').val();
        var batch_no = $(this).val();

        var warehouse_id = $('#warehouse_id').val();

        console.log('product_id', product_id, 'batch_no', batch_no);

        $.get('../check-batch-availability/' + product_id + '/' + batch_no + '/' + warehouse_id, function(data) {
            console.log('Responseee:', data);

            if (data['message'] !== 'ok') {
                alert(data['message']);

                // Reset batch details if not available
                row.find('.batch-no').val('');
                row.find('.product-batch-id').val('');
                row.find('.expired-date').text('');
                row.find('.net_unit_price').text('');
            } else {
                // Update batch details if available
                row.find('.product-batch-id').val(data['product_batch_id']);
                row.find('.expired-date').text(data['expired_date']);
                row.find('.net_unit_price').text(data['price']);
                row.find('.unit_cost').text(data['price']);

                var code = row.find('.product-code').val();
                var pos = product_code.indexOf(code);
                product_qty[pos] = data['qty'];
                // unit_cost[0] = data['price'];

                row.find('.net_unit_price').val(data['price']);
                row.find('.net_unit_price1').val(data['price']);
                console.log(row.find('.net_unit_price').text());



            }

            // Call checkQuantity function to verify stock availability
            checkQuantity(String(row.find('.qty').val()), true);
        });
    });







	function productSearch(data){
		$.ajax({
            type: 'GET',
            url: 'lims_product_search',
            data: {
                data: data
            },
            success: function(data) {
            	console.log('search-data',data);
                var flag = 1;
                $(".product-code").each(function(i) {
                    if ($(this).val() == data[1]) {
                        rowindex = i;
	                    var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
	                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
	                    checkQuantity(qty);
	                    flag = 0;
                    }
                });
                $("input[name='product_code_name']").val('');
                if(flag){
                    var newRow = $("<tr>");
                    var cols = '';
                    pos = product_code.indexOf(data[1]);
                    cols += '<td>' + data[0] + '</td>';
                    // cols += '<td>' + data[4] + '</td>';
                    cols += '<td><select class="form-control batch-no1" name="product_batch_id1[]"></select>';
                    cols += '<input type="hidden" class="batch-no" name="product_batch_id_hidden[]" value="" />';
                    cols += '<input type="hidden" class="product-batch-id" name="product_batch_id[]" value=""/></td>';

                    cols += '<td>' + data[1] + '</td>';
                    // cols += '<td>' + data[5] + '<input type="text" name="unit_cost[]" class="net_unit_price" value="'+data[5]+'" /></td>';
                    cols += '<td>' + '<input type="text" name="unit_cost1[]" class="form-control net_unit_price1" value="'+data[5]+'" readonly />' + '<input type="hidden" name="unit_cost[]" class="net_unit_price" value="'+data[5]+'" /></td>';
                    // cols += '<td name="unit_cost[] value="" class="net_unit_price">'+unit_cost[pos]+'</td>';
                    cols += '<td><input type="number" class="form-control qty" name="qty[]" value="1" required step="any" /></td>';
                    cols += '<td class="action"><select name="action[]" class="form-control act-val"><option value="-">{{trans("file.Subtraction")}}</option><option value="+">{{trans("file.Addition")}}</option></select></td>';
                    cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{trans("file.delete")}}</button></td>';
                    cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                    cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[2] + '"/>';

                    newRow.append(cols);
                    $("table.order-list tbody").append(newRow);

                    var batchDropdown = newRow.find('.batch-no1');

                    $.get('../get-batches/' + data[2] + '/' + data[3], function (data1) {
                        console.log('Available Batches:', data1);

                        batchDropdown.empty();

                        if (data1.length > 0) {
                            batchDropdown.append('<option value="">Select Batch</option>');

                            data1.forEach(function (batch) {
                                console.log('show batch data',batch);
                                batchDropdown.append('<option value="' + batch.batch_no + '">' + batch.batch_no + '</option>');
                            });
                        } else {
                            batchDropdown.append('<option value="">No Batches Available</option>');
                        }
                    }).fail(function () {
                        console.error("Error fetching batches. Check API endpoint.");
                    });
                    rowindex = newRow.index();
                    calculateTotal();
                }
            }
        });
	}

	function checkQuantity(qty) {
	    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
	    var action = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.act-val').val();
	    var pos = product_code.indexOf(row_product_code);

	    if ( (qty > parseFloat(product_qty[pos])) && (action == '-') ) {
	        alert('Quantity exceeds stock quantity!');
            var row_qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
            row_qty = row_qty.substring(0, row_qty.length - 1);
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(row_qty);
	    }
	    else {
	        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(qty);
	    }
	    calculateTotal();
	}

	function calculateTotal() {
	    var total_qty = 0;
	    $(".qty").each(function() {

	        if ($(this).val() == '') {
	            total_qty += 0;
	        } else {
	            total_qty += parseFloat($(this).val());
	        }
	    });
	    $("#total-qty").text(total_qty);
	    $('input[name="total_qty"]').val(total_qty);
	    $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
	}
</script>
@endpush
