<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/png" href="{{url('logo', $general_setting->site_logo)}}" />
        <title>{{$lims_sale_data->customer->name.'_Sale_'.$lims_sale_data->reference_no}}</title>
        <style type="text/css">
            span,td {
                font-size: 13px;
                line-height: 1.4;
            }
            body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f5f5f5;
      min-height: 100vh;
      position: relative;
      padding-bottom: 200px; 
    }
    .invoice-box {
      max-width: 800px;
      margin: auto;
      padding: 30px;
      background-color: #fff;
      /*border: 1px solid #eee;*/
      /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);*/
    }
    .top-section,
    .addresses,
    .summary,
    .notes {
      margin-bottom: 20px;
    }
    .top-section {
      display: flex;
      justify-content: space-between;
    }
    .top-section .company {
      font-size: 20px;
      font-weight: bold;
    }
    .invoice-details {
      text-align: right;
      font-size: 14px;
    }
    .logo {
      background-color: #ddd;
      width: 200px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #999;
      font-size: 16px;
      margin: 10px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      text-align: left;
      border: 1px solid #5D2FA3;
    }
    th {
      background-color: #5D2FA3;
      color: #fff;
    }
    .summary td {
      text-align: right;
    }
    .summary td:first-child {
      text-align: left;
    }
   .footer-wrapper {
    page-break-inside: avoid;
    page-break-before: avoid;
    page-break-after: avoid;
    break-inside: avoid;
    break-before: avoid;
    break-after: avoid;
    position: absolute;
  }

  .footer {
      text-align: center;
      font-size: 15px;
      color: #555;
      padding: 30px;
      padding-top: 10px !important;
      background-color: #fff;
      border-top: 2px solid #5d2fa3;
    }

    .footer-border {
      border-top: 1px solid #ccc;
      margin: 5px auto;
      width: 80%;
    }

    .footer-address {
      font-weight: bold;
      font-style: italic;
      margin: 10px 0;
    }

    .footer-contact {
      white-space: nowrap;
      font-size: 14px;
      margin-top: 10px;
    }

    .footer-contact div {
      display: inline;
      margin: 0 5px;
    }
            @media print {
                .hidden-print {
                    display: none !important;
                }
                tr.table-header {
                    background-color:#5d2fa3 !important;
                    -webkit-print-color-adjust: exact;
                }
                td.td-text {
                    background-color:#e2d0fc !important;
                    -webkit-print-color-adjust: exact;
                }
               
                body {
        margin: 0;
        padding-bottom: 0;
      }

      .footer-wrapper {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    page-break-inside: avoid;
  }

  .footer-contact {
    display: flex !important;
    justify-content: center !important;
    gap: 10px;
    flex-wrap: wrap !important;
    text-align: center !important;
  }

  .footer-contact div {
    display: block !important;
  }
  
  }
            }
            table,tr,td {font-family: sans-serif;border-collapse: collapse;}
        </style>
    </head>
    <body>
        @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = '../../pos'; @endphp
        @else
            @php $url = url()->previous(); @endphp
        @endif
        <div class="hidden-print">
            <table>
                <tr>
                    <td><a href="{{$url}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> {{trans('file.Back')}}</a> </td>
                    <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> {{trans('file.Print')}}</button></td>
                </tr>
            </table>
            <br>
        </div>
        <div class="invoice-box">
            <div class="top-section">
              <div>
                
                <div style="width:30%; text-align: middle; vertical-align: top;">
                    <img src="{{url('logo', $general_setting->site_logo)}}" height="120" width="auto">
                    
                </div>
              </div>
              <div>
                  <h1 style="color: #5D2FA3;">
                      INVOICE
                  </h1>
              </div>
              <div class="invoice-details">
                <p><strong>Date:</strong> {{$lims_sale_data->created_at}}</p>
                <p><strong>Invoice #:</strong> {{$lims_sale_data->reference_no}}</p>
                <p><strong>Paid By:</strong> {{$paid_by_info}}</p>
              </div>
            </div>
        
            <div class="addresses">
              <table>
                <tr class="table-header" style="background-color: #5D2FA3; color: white;">
                  <th>Bill To:</th>
                  
                </tr>
                <tr>
                  <td>
                    Name: {{$lims_customer_data->name}}<br>
                    Address: {{$lims_customer_data->address}}<br>
                    Phone: {{$lims_customer_data->phone_number}}
                  </td>
                  
                </tr>
              </table>
            </div>
     
    
        <table dir="@if( Config::get('app.locale') == 'ar' || $general_setting->is_rtl){{'rtl'}}@endif" style="width: 100%;border-collapse: collapse;">
            <tr class="table-header" style="background-color: #5D2FA3; color: white;">
                <td style="border:1px solid #222;padding:1px 3px;width:4%;text-align:center">#</td>
                <td style="border:1px solid #222;padding:1px 3px;width:49%;text-align:center">{{trans('file.Description')}}</td>
                <td style="border:1px solid #222;padding:1px 3px;width:6%;text-align:center">{{trans('Model')}}</td>
                <td style="border:1px solid #222;padding:1px 3px;width:6%;text-align:center">{{trans('file.Qty')}}</td>
                <td style="border:1px solid #222;padding:1px 3px;width:9%;text-align:center">{{trans('Actual Price')}}</td>
                <td style="border:1px solid #222;padding:1px 3px;width:7%;text-align:center">{{trans('Discount')}}</td>
                <td style="border:1px solid #222;padding:1px 3px;width:7%;text-align:center">{{trans('Discounted Rate')}}</td>
                <td style="border:1px solid #222;padding:1px 2px;width:13%;text-align:center;">{{trans('Total Taka')}}</td>
            </tr>
            <?php
                $total_product_tax = 0;
                $totalPrice = 0;
            ?>
            
            @foreach($lims_product_sale_data as $key => $product_sale_data)
            <?php
                $lims_product_data = \App\Models\Product::find($product_sale_data->product_id);
                if($product_sale_data->sale_unit_id) {
                    $unit = \App\Models\Unit::select('unit_code')->find($product_sale_data->sale_unit_id);
                    $unit_code = $unit->unit_code;
                }
                else
                    $unit_code = '';

                if($product_sale_data->variant_id) {
                    $variant = \App\Models\Variant::select('name')->find($product_sale_data->variant_id);
                    $variant_name = $variant->name;
                }
                else
                    $variant_name = '';
                $totalPrice += $product_sale_data->net_unit_price * $product_sale_data->qty;

                $topping_names = [];
                $topping_prices = [];
                $topping_price_sum = 0;
        
                if ($product_sale_data->topping_id) {
                    $decoded_topping_id = json_decode(json_decode($product_sale_data->topping_id), true);
                    //dd(json_decode($product_sale_data->topping_id));
                    if (is_array($decoded_topping_id)) {
                        foreach ($decoded_topping_id as $topping) {
                            $topping_names[] = $topping['name']; // Extract name
                            $topping_prices[] = $topping['price']; // Extract price
                            $topping_price_sum += $topping['price']; // Sum up prices
                        }
                    }
                }
        
                $net_price_with_toppings = $product_sale_data->net_unit_price + $topping_price_sum;
                $total = ($product_sale_data->net_unit_price + $topping_price_sum) * $product_sale_data->qty;

                $subtotal = ($product_sale_data->total+ $topping_price_sum);
            ?>
            <tr>
                <td style="@if( Config::get('app.locale') == 'ar' || $general_setting->is_rtl){{'border-right:1px solid #222;'}}@endif border:1px solid #222;padding:1px 3px;text-align: center;">{{$key+1}}</td>
                <td style="border:1px solid #222;padding:1px 3px;font-size: 15px;line-height: 1.2;">

                    <span style="font-weight: bold;">Product Name</span>: 

                    {!!$lims_product_data->name!!}

                    @if(!empty($topping_names))
                        <br><small>({{ implode(', ', $topping_names) }})</small>
                    @endif

                    @foreach($product_custom_fields as $index => $fieldName)
                        <?php $field_name = str_replace(" ", "_", strtolower($fieldName)) ?>
                        @if($lims_product_data->$field_name)
                            @if(!$index)
                            <br>
                            <span style="font-weight: bold;">{{ $fieldName }}</span>
                            {{ ': ' . $lims_product_data->$field_name }}
                            @else
                            <br>
                            <span style="font-weight: bold;">{{ $fieldName }}</span>
                            {{': ' . $lims_product_data->$field_name }}
                            @endif
                        @endif
                    @endforeach
                    @if($product_sale_data->imei_number && !str_contains($product_sale_data->imei_number, "null") )
                    <br>IMEI or Serial: {{$product_sale_data->imei_number}}
                    @endif
                    <!-- warranty -->
                     {{--@if (isset($product_sale_data->warranty_duration))
                            <br>
                            <span style="font-weight: bold;">Warranty</span>{{ ': ' . $product_sale_data->warranty_duration }}
                            <br>
                            <span style="font-weight: bold;">Will Expire</span>{{ ': ' . $product_sale_data->warranty_end }}
                     @endif--}}
                     
                     
                     
                     
                     
                   @php
    $saleDate = $lims_product_data->sale_date ?? $lims_product_data->created_at; // use correct sale date field

    $warrantyOnExpiry = $lims_product_data->warranty
        ? \Carbon\Carbon::parse($saleDate)->addYears($lims_product_data->warranty)->format('d M, Y')
        : 'N/A';

    $partsWarrantyExpiry = $lims_product_data->warranty2
        ? \Carbon\Carbon::parse($saleDate)->addYears($lims_product_data->warranty2)->format('d M, Y')
        : 'N/A';

    $saleServiceExpiry = $lims_product_data->sale_service_warranty
        ? \Carbon\Carbon::parse($saleDate)->addYears($lims_product_data->sale_service_warranty)->format('d M, Y')
        : 'N/A';
@endphp


<small>
    <br>
    <span style="font-weight: bold;">Warranty On</span>: {{ $lims_product_data->warranty_on_type }}
    - {{ $lims_product_data->warranty }} Years (Will Expire: {{ $warrantyOnExpiry }})

    <br>
    <span style="font-weight: bold;">Parts Warranty</span>: {{ $lims_product_data->parts_warranty }}
@if($lims_product_data->warranty2 == 100)
    – Lifetime
@else
    – {{ $lims_product_data->warranty2 }} Years (Will Expire: {{ $partsWarrantyExpiry }})
@endif


    <br>
    <span style="font-weight: bold;">Sale Service Warranty</span>: {{ $lims_product_data->sale_service_warranty }} Years (Will Expire: {{ $saleServiceExpiry }})
</small>




                            
                            
                            
                            
                            
                            
                            
                            
                            
                     <!-- guarantee -->
                     @if (isset($product_sale_data->guarantee_duration))
                            <br>
                            <span style="font-weight: bold;">Guarantee</span>{{ ': ' . $product_sale_data->guarantee_duration }}
                            <br>
                            <span style="font-weight: bold;">Will Expire</span>{{ ': ' . $product_sale_data->guarantee_end }}
                     @endif
                </td>
                <td style="border:1px solid #222;padding:1px 3px;text-align:center">{{$product_sale_data->product_model}}</td>
                <td style="border:1px solid #222;padding:1px 3px;text-align:center">{{$product_sale_data->qty.' '.$unit_code.' '.$variant_name}}</td>
                <td style="border:1px solid #222;padding:1px 3px;text-align:center">{{number_format($product_sale_data->unit_price, $general_setting->decimal, '.', ',')}}
                
                </td>
                <td style="border:1px solid #222;padding:1px 3px;text-align:center">{{ number_format($product_sale_data->unit_discount, $general_setting->decimal, '.', ',') }}</td>
                <td style="border:1px solid #222;padding:1px 3px;text-align:center">{{number_format($product_sale_data->net_unit_price, $general_setting->decimal, '.', ',')}}</td>
                <td style="border:1px solid #222;border-right:1px solid #222;padding:1px 3px;text-align:center;font-size: 15px;">{{number_format($subtotal, $general_setting->decimal, '.', ',')}}</td>
            </tr>
            @endforeach
            
            <tr>
                <td colspan="4" rowspan="@if($general_setting->invoice_format == 'gst' && $general_setting->state == 2) 5 @else 4 @endif" style="border:1px solid #222;padding:1px 3px;text-align: center; vertical-align: top;">
                    {{trans('file.Note')}}<br>{{$lims_sale_data->sale_note}}
                </td>
                <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;font-weight: bold;font-weight: bold;">
                    {{trans('file.Total Before Tax')}}
                </td>
                <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px; font-weight: bold;">
                        {{number_format((float)($lims_sale_data->total_price - ($lims_sale_data->total_tax+$lims_sale_data->order_tax) ) ,$general_setting->decimal, '.', ',')}}
                </td>
            </tr>
            @if($general_setting->invoice_format == 'gst' && $general_setting->state == 1)
                <tr>
                    <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;">
                        IGST
                    </td>
                    <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px;">
                        {{number_format((float)($lims_sale_data->total_tax+$lims_sale_data->order_tax) ,$general_setting->decimal, '.', ',')}}
                    </td>
                </tr>
            
            @else
                <tr>
                    <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;">
                        {{trans('file.Tax')}}
                    </td>
                    <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px;">
                        {{number_format((float)($lims_sale_data->total_tax+$lims_sale_data->order_tax) ,$general_setting->decimal, '.', ',')}}
                    </td>
                </tr>
            @endif
            <tr>
                <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;">
                    {{trans('Other Discount')}}
                </td>
                <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px;">
                    {{number_format((float)($lims_sale_data->order_discount) ,$general_setting->decimal, '.', ',')}}
                </td>
            </tr>
           
            <tr>
                <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;">
                    {{trans('Shipping Cost')}}
                </td>
                <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px;">
                    {{number_format((float)($lims_sale_data->shipping_cost) ,$general_setting->decimal, '.', ',')}}
                </td>
            </tr>
            
            <tr>
                @if($general_setting->currency_position == 'prefix')
                    <td class="td-text" colspan="4" rowspan="5" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;vertical-align: bottom;font-size: 15px; vertical-align: top;">
                        {{trans('file.In Words')}}<br>{{$currency_code}} <span style="text-transform:capitalize;font-size: 15px;">{{str_replace("-"," ",$numberInWords)}}</span> only
                    </td>
                @else
                    <td class="td-text" colspan="4" rowspan="5" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;vertical-align: bottom;font-size: 15px; vertical-align: top;">
                        {{trans('file.In Words')}}:<br><span style="text-transform:capitalize;font-size: 15px;">{{str_replace("-"," ",$numberInWords)}}</span> {{$currency_code}} only
                    </td>
                @endif
            </tr>
            <tr>
                <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc; font-weight: bold;">{{trans('file.grand total')}}</td>
                <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px;font-weight: bold;">{{number_format((float)$lims_sale_data->grand_total ,$general_setting->decimal, '.', ',')}}</td>
            </tr>
            <tr>
                <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;">
                    {{trans('file.Paid')}}
                </td>
                <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px;">
                    {{number_format((float)$lims_sale_data->paid_amount ,$general_setting->decimal, '.', ',')}}
                </td>
            </tr>
            <tr>
                <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;">
                    {{trans('file.Due')}}
                </td>
                <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px;">
                    {{number_format((float)($lims_sale_data->grand_total - $lims_sale_data->paid_amount) ,$general_setting->decimal, '.', ',')}}
                </td>
            </tr>
            <tr>
                <td class="td-text" colspan="3" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;">
                    {{trans('file.Total Due')}}
                </td>
                <td class="td-text" style="border:1px solid #222;padding:1px 3px;background-color:#e2d0fc;text-align: center;font-size: 15px;">
                    {{number_format($totalDue ,$general_setting->decimal, '.', ',')}}
                </td>
            </tr>
        </table>
        <div style="width: 100%; text-align: center;  margin-top: 0px; padding-bottom: 10px;">
    <br>
    <?php echo '<img style="max-width:100%" src="data:image/png;base64,' . DNS1D::getBarcodePNG($lims_sale_data->reference_no, 'C128') . '" alt="barcode" />'; ?>
    <br><br>
    <?php echo '<img style="width:5%" src="data:image/png;base64,' . DNS2D::getBarcodePNG($qrText, 'QRCODE') . '" alt="barcode" />'; ?>
</div>

<div class="footer-wrapper">
  <div class="footer">
    Thank you for your purchase!<br>
    We appreciate your support and look forward to serving you again.<br>
    Your satisfaction means everything to us — please visit again!

    <div class="footer-address">
      𝗦𝗵𝗼𝗽 𝟱𝟰, 𝗖𝗮𝗽𝗶𝘁𝗮𝗹 𝗦𝘂𝗽𝗲𝗿 𝗠𝗮𝗿𝗸𝗲𝘁, 𝟭𝟬𝟰 𝗚𝗿𝗲𝗲𝗻 𝗥𝗼𝗮𝗱, 𝗙𝗮𝗿𝗺𝗴𝗮𝘁𝗲, 𝗗𝗵𝗮𝗸𝗮-𝟭𝟮𝟭𝟱, 𝗕𝗮𝗻𝗴𝗹𝗮𝗱𝗲𝘀𝗵
    
    <div class="footer-border"></div>

    <div class="footer-contact" >
      <div><strong>Phone:</strong> 01712-986688</div>
      <div><strong>Email:</strong> crockeriespark.bd@gmail.com</div>
      <div><strong>Website:</strong> www.crockerispark.com</div>
    </div>

    </div>
  </div>
</div>
</div>
        <script type="text/javascript">
            localStorage.clear();
            function auto_print() {
                window.print();

            }
            //setTimeout(auto_print, 1000);
        </script>
    </body>
</html>
