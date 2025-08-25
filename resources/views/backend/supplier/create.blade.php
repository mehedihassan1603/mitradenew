@extends('backend.layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Add Supplier')}}</h4>
                    </div>
                    <div class="card-body">
{{--                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>--}}
                        {!! Form::open(['route' => 'supplier.store', 'method' => 'post', 'files' => true]) !!}
                        <div class="row">
{{--                            <div class="col-md-4 mt-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <input type="checkbox" name="both" value="1" />&nbsp;--}}
{{--                                    <label>{{trans('file.Both Customer and Supplier')}}</label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-md-4 customer-group-section">
                                <div class="form-group">
                                    <label>{{trans('file.Customer Group')}} *</strong> </label>
                                    <select class="form-control selectpicker" id="customer-group-id" name="customer_group_id">
                                        @foreach($lims_customer_group_all as $customer_group)
                                            <option value="{{$customer_group->id}}">{{$customer_group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.name')}} *</strong> </label>
                                    <input type="text" name="name" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Image')}}</label>
                                    <input type="file" name="image" class="form-control">
                                    @if($errors->has('image'))
                                   <span>
                                       <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Company Name')}} *</label>
                                    <input type="text" name="company_name" required class="form-control">
                                    @if($errors->has('company_name'))
                                   <span>
                                       <strong>{{ $errors->first('company_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('Supplier Type') }}</label>
                                    <select name="supplier-type" class="form-control" required>
                                        <option value="" disabled selected>{{ trans('Select Supplier Type') }}</option>
                                        <option value="Supplier">{{ trans('Supplier') }}</option>
                                        <option value="Exporter">{{ trans('Exporter') }}</option>
                                        <option value="Door-to-Door">{{ trans('Door-to-Door') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.VAT Number')}}</label>
                                    <input type="text" name="vat_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Email')}} *</label>
                                    <input type="email" name="email" placeholder="example@example.com" required class="form-control">
                                    @if($errors->has('email'))
                                   <span>
                                       <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Phone Number')}} *</label>
                                    <input type="text" name="phone_number" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Address')}} *</label>
                                    <input type="text" name="address" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.City')}} *</label>
                                    <input type="text" name="city" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.State')}}</label>
                                    <input type="text" name="state" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Postal Code')}}</label>
                                    <input type="text" name="postal_code" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{trans('file.Country')}}</label>
                                    <input type="text" name="country" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-4">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

   {{--supplier bank details --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('Supplier Bank Details')}}</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 customer-group-section">
                                    <div class="form-group">
                                        <label>{{trans('file.Customer Group')}} *</strong> </label>
                                        <select class="form-control selectpicker" id="customer-group-id" name="customer_group_id">
                                            @foreach($lims_customer_group_all as $customer_group)
                                                <option value="{{$customer_group->id}}">{{$customer_group->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('Bank Name')}}</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('Branch Name')}}</label>
                                        <input type="text" name="branch_name" id="branch_name" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('Account Number')}}</label>
                                        <input type="text" name="account_number" id="account_number" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('Account Type')}}</label>
                                        <select name="account-type" class="form-control">
                                            <option value="" disabled selected>{{ trans('Select Account Type') }}</option>
                                            <option value="Current">{{ trans('Current') }}</option>
                                            <option value="Savings">{{ trans('Savings') }}</option>
                                            <option value="FC Account">{{ trans('FC Account') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('SWIFT Code')}}</label>
                                        <input type="text" name="swift_code" id="swift_code" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('Routing Number')}}</label>
                                        <input type="text" name="routing_number" id="routing_number" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('IBAN Number')}}</label>
                                        <input type="text" name="iban_number" id="iban_number" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('Currency Type')}}</label>
                                        <input type="text" name="currency_type" id="currency_type" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('Bank Address')}}</label>
                                        <input type="text" name="bank_address" id="bank_address" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans(' Mobile Banking Info')}}</label>
                                        <input type="text" name="mobile_banking_info" id="mobile_banking_info" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mt-4">
                                        <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

@endsection

@push('scripts')
<script type="text/javascript">
    $("ul#people").siblings('a').attr('aria-expanded','true');
    $("ul#people").addClass("show");
    $("ul#people #supplier-create-menu").addClass("active");
    $(".customer-group-section").hide();

    $('input[name="both"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('.customer-group-section').show(300);
            $('select[name="customer_group_id"]').prop('required',true);
        }
        else{
            $('.customer-group-section').hide(300);
            $('select[name="customer_group_id"]').prop('required',false);
        }
    });
</script>
@endpush
