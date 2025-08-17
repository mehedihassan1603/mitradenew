<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Product_Warehouse;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Adjustment;
use App\Models\ProductAdjustment;
use App\Models\ProductBatch;
use DB;
use App\Models\StockCount;
use App\Models\ProductVariant;
use App\Models\ProductPurchase;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdjustmentController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if( $role->hasPermissionTo('adjustment') ) {
            /*if(Auth::user()->role_id > 2 && config('staff_access') == 'own')
                $lims_adjustment_all = Adjustment::orderBy('id', 'desc')->where('user_id', Auth::id())->get();
            else*/
                $lims_adjustment_all = Adjustment::orderBy('id', 'desc')->get();
            return view('backend.adjustment.index', compact('lims_adjustment_all'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }











    public function modelCreate()
{

    $products = Product::all();
    $models = ProductModel::with('product')->latest()->get();
    return view('backend.model.create', compact('products', 'models'));
}

public function modelStore(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        // 'product_id' => 'required|exists:products,id',
        'image' => 'nullable|image|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $destinationPath = public_path('uploads/models');

        // Ensure the directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $image->move($destinationPath, $imageName);
        $imagePath = 'uploads/models/' . $imageName;
    }


    ProductModel::create([
        'name' => $request->name,
        // 'product_id' => $request->product_id,
        'image' => $imagePath,
    ]);

    return back()->with('success', 'Model added successfully');
}

public function modelDelete($id)
{
    $model = ProductModel::findOrFail($id);
    if ($model->image && file_exists(public_path('storage/' . $model->image))) {
        unlink(public_path('storage/' . $model->image));
    }
    $model->delete();
    return back()->with('success', 'Model deleted successfully');
}



















    public function getProduct($id)
    {
        // $lims_product_warehouse_data = DB::table('products')
        //                             ->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
        //                             ->join('product_purchases', function ($join) {
        //                                 $join->on('products.id', '=', 'product_purchases.product_id')
        //                                     ->on('product_warehouse.product_batch_id', '=', 'product_purchases.product_batch_id');
        //                             })
        //                             ->whereNull('products.is_variant')
        //                             ->where([
        //                                 ['products.is_active', true],
        //                                 ['product_warehouse.warehouse_id', $id]
        //                             ])
        //                             ->select(
        //                        'product_purchases.net_unit_cost',
        //                                 'product_warehouse.product_batch_id',
        //                                 'product_warehouse.warehouse_id',
        //                                 'product_warehouse.qty',
        //                                 'products.code',
        //                                 'products.name',
        //                                 'product_warehouse.product_id',
        //                                 'products.cost',
        //                                 'products.price'
        //                             )
        //                             ->get();
        $lims_product_warehouse_data = DB::table('products')
    ->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
    ->join('product_purchases', function ($join) {
        $join->on('products.id', '=', 'product_purchases.product_id')
            ->on('product_warehouse.product_batch_id', '=', 'product_purchases.product_batch_id');
    })
    ->whereNull('products.is_variant')
    ->where([
        ['products.is_active', true],
        ['product_warehouse.warehouse_id', $id]
    ])
    ->select(
        'product_purchases.net_unit_cost',
        DB::raw('MAX(product_warehouse.product_batch_id) as product_batch_id'),
        'product_warehouse.warehouse_id',
        'product_warehouse.qty',
        'products.code',
        'products.name',
        'product_warehouse.product_id',
        'products.cost',
        'products.price'
    )
    ->groupBy(
        'product_purchases.net_unit_cost',
        'product_warehouse.warehouse_id',
        'product_warehouse.qty',
        'products.code',
        'products.name',
        'product_warehouse.product_id',
        'products.cost',
        'products.price'
    )
    ->get();


                                    // dd($lims_product_warehouse_data);
        $lims_product_withVariant_warehouse_data = DB::table('products')
                                    ->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
                                    ->whereNotNull('products.is_variant')
                                    ->where([
                                        ['products.is_active', true],
                                        ['product_warehouse.warehouse_id', $id]
                                    ])
                                    ->select('products.name', 'product_warehouse.qty', 'product_warehouse.product_id', 'product_warehouse.variant_id', 'products.cost')
                                    ->get();
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_cost = [];
        $product_batch_id = [];
        $net_unit_cost = [];
        $product_data = [];
        foreach ($lims_product_warehouse_data as $product_warehouse)
        {
            $product_qty[] = $product_warehouse->qty;
            $product_code[] =  $product_warehouse->code;
            $product_name[] = $product_warehouse->name;
            $product_batch_id[] = $product_warehouse->product_batch_id;
            $net_unit_cost[] = $product_warehouse->net_unit_cost;
            $query = array(
                    'SUM(qty) AS total_qty',
                    'SUM(total) AS total_cost'
                );
            $product_purchase_data = ProductPurchase::join('purchases', 'product_purchases.product_id', '=', 'purchases.id')
                                    ->where([
                                        ['product_id', $product_warehouse->product_id],
                                        ['warehouse_id', $id]
                                    ])->selectRaw(implode(',', $query))->get();

            // if(count($product_purchase_data) && $product_purchase_data[0]->total_qty > 0)
            //     $product_cost[] = $product_purchase_data[0]->total_cost / $product_purchase_data[0]->total_qty;

            // else
                $product_cost[] = $product_warehouse->cost;


                // dd('abc',$product_cost);
        }

        foreach ($lims_product_withVariant_warehouse_data as $product_warehouse)
        {
            $product_variant = ProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
            if($product_variant) {
                $product_qty[] = $product_warehouse->qty;
                $product_code[] =  $product_variant->item_code;
                $product_name[] = $product_warehouse->name;
                $query = array(
                    'SUM(qty) AS total_qty',
                    'SUM(total) AS total_cost'
                );
                $product_purchase_data = ProductPurchase::join('purchases', 'product_purchases.product_id', '=', 'purchases.id')
                                        ->where([
                                            ['product_id', $product_warehouse->product_id],
                                            ['variant_id', $product_warehouse->variant_id],
                                            ['warehouse_id', $id]
                                        ])->selectRaw(implode(',', $query))->get();
                // if(count($product_purchase_data) && $product_purchase_data[0]->total_qty > 0)
                //     $product_cost[] = $product_purchase_data[0]->total_cost / $product_purchase_data[0]->total_qty;
                // else
                    $product_cost[] = $product_warehouse->cost;
                }
        }

        $product_data[] = $product_code;
        $product_data[] = $product_name;
        $product_data[] = $product_qty;
        $product_data[] = $product_cost;
        $product_data[] = $product_batch_id;
        $product_data[] = $net_unit_cost;
        // dd($product_data);
        return $product_data;
    }

    // public function limsProductSearch(Request $request)
    // {
    //     // dd($request->all());
    //     $batch_parts = explode("=", $request['data']);
    //     $product_batch_id = explode(">", $batch_parts[1])[0];

    //     $price_parts = explode(">", $batch_parts[1]);
    //     $price = $price_parts[1];
    //     // dd($product_batch_id, $price);
    //     $product_code = explode("(", $request['data']);
    //     $product_info = explode("|", $request['data']);
    //     $value_part = explode("=", $product_info[1])[0];
    //     // dd($value_part);
    //     $product_code[0] = rtrim($product_code[0], " ");
    //     $lims_product_data = Product::where([
    //         ['code', $product_code[0]],
    //         ['is_active', true]
    //     ])->first();
    //     dd('dssf');
    //     if(!$lims_product_data) {
    //         $lims_product_data = Product::join('product_variants', 'products.id', 'product_variants.product_id')
    //             ->select('products.id', 'products.name', 'products.is_variant', 'product_variants.id as product_variant_id', 'product_variants.item_code')
    //             ->where([
    //                 ['product_variants.item_code', $product_code[0]],
    //                 ['products.is_active', true]
    //             ])->first();
    //     }

    //     dd('lims data',$lims_product_data);

    //     $product[] = $lims_product_data->name;
    //     $product_variant_id = null;
    //     if($lims_product_data->is_variant) {
    //         $product[] = $lims_product_data->item_code;
    //         $product_variant_id = $lims_product_data->product_variant_id;
    //     }
    //     else
    //         $product[] = $lims_product_data->code;

    //     $product[] = $lims_product_data->id;
    //     $product[] = $product_variant_id;
    //     $product[] = $value_part;
    //     $product[] = $product_batch_id;
    //     $product[] = $price;
    //     dd($product);
    //     return $product;

    // }


    public function limsProductSearch(Request $request)
    {
        // dd($request->all());
        $product_code = explode("(", $request['data']);
        $product_info = explode("|", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $lims_product_data = Product::where([
            ['code', $product_code[0]],
            ['is_active', true]
        ])->first();
        if (!$lims_product_data) {
            $lims_product_data = Product::join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->leftJoin('product_batches', 'products.id', '=', 'product_batches.product_id')
                ->leftJoin('product_warehouse', function ($join) {
                    $join->on('products.id', '=', 'product_warehouse.product_id')
                         ->on('product_batches.id', '=', 'product_warehouse.product_batch_id')
                         ->on('product_variants.id', '=', 'product_warehouse.variant_id');
                })
                ->select(
                    'products.id',
                    'product_batches.batch_no as product_batch_id',
                    'products.name',
                    'products.is_batch',
                    'products.is_variant',
                    'product_variants.id as product_variant_id',
                    'product_variants.item_code'
                )
                ->where([
                    ['product_variants.item_code', $product_code[0]],
                    ['products.is_active', true]
                ])
                ->first();
        }




        $product[] = $lims_product_data->name;
        $product_variant_id = null;
        $product_batch_id = null;
        if($lims_product_data->is_batch) {
            $product_batch_id = $lims_product_data->product_batch_id;
        }
        if($lims_product_data->is_variant) {
            $product[] = $lims_product_data->item_code;
            $product_variant_id = $lims_product_data->product_variant_id;
        }
        else
            $product[] = $lims_product_data->code;

        $product[] = $lims_product_data->id;
        $product[] = $product_variant_id;
        $product[] = $product_batch_id;
        $product[] = $product_info[1];
        // dd('product', $product);
        return $product;
    }

    public function create()
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        return view('backend.adjustment.create', compact('lims_warehouse_list'));
    }
    public function addSalePrice()
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        return view('backend.adjustment.salePrice', compact('lims_warehouse_list'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->except('document');
        //return $data;
        if( isset($data['stock_count_id']) ){
            $lims_stock_count_data = StockCount::find($data['stock_count_id']);
            $lims_stock_count_data->is_adjusted = true;
            $lims_stock_count_data->save();
        }
        $data['reference_no'] = 'adr-' . date("Ymd") . '-'. date("his");
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move(public_path('documents/adjustment'), $documentName);
            $data['document'] = $documentName;
        }
        $lims_adjustment_data = Adjustment::create($data);

        $product_id = $data['product_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $unit_cost = $data['unit_cost'];
        $action = $data['action'];

        foreach ($product_id as $key => $pro_id) {
            $product_batch_id1 = $data['product_batch_id'][$key] ?? null;
            // dd('Batch Id',$product_batch_id1);
            $lims_product_data = Product::find($pro_id);
            if($lims_product_data->is_variant) {
                // dd('aa', $lims_product_data );
                $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $pro_id],
                    ['variant_id', $lims_product_variant_data->variant_id ],
                    ['warehouse_id', $data['warehouse_id'] ],
                ])->first();

                if($action[$key] == '-'){
                    $lims_product_variant_data->qty -= $qty[$key];
                }
                elseif($action[$key] == '+'){
                    $lims_product_variant_data->qty += $qty[$key];
                }
                $lims_product_variant_data->save();
                $variant_id = $lims_product_variant_data->variant_id;



                $lims_batch_data = Product::join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->leftJoin('product_batches', 'products.id', '=', 'product_batches.product_id')
                ->leftJoin('product_warehouse', function ($join) {
                    $join->on('products.id', '=', 'product_warehouse.product_id')
                         ->on('product_batches.id', '=', 'product_warehouse.product_batch_id')
                         ->on('product_variants.id', '=', 'product_warehouse.variant_id');
                })
                ->select(
                    'products.id',
                    'product_batches.id as product_batch_id',
                    'product_batches.qty as product_batch_qty',
                    'products.name',
                    'products.is_batch',
                )
                ->where([
                    ['product_variants.item_code', $product_code[0]],
                    // ['product_variants.item_code', $product_code[$key]],
                    ['products.is_active', true]
                ])
                ->first();
            }
            else {
                // dd('bb', $lims_product_data );
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $pro_id],
                    ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                $variant_id = null;

                $lims_batch_data = Product::leftJoin('product_batches', 'products.id', '=', 'product_batches.product_id')
                ->leftJoin('product_warehouse', function ($join) {
                    $join->on('products.id', '=', 'product_warehouse.product_id')
                         ->on('product_batches.id', '=', 'product_warehouse.product_batch_id');
                })
                ->select(
                    'products.id',
                    'product_batches.id as product_batch_id',
                    'product_batches.qty as product_batch_qty',
                    'products.name',
                    'products.is_batch',
                )
                ->where([
                    ['products.code', $product_code[0]],
                    // ['product_variants.item_code', $product_code[$key]],
                    ['products.is_active', true]
                ])
                ->first();
            }

            // dd('aa', $product_code[0]);

            // $lims_batch_data = Product::join('product_variants', 'products.id', '=', 'product_variants.product_id')
            //     ->leftJoin('product_batches', 'products.id', '=', 'product_batches.product_id')
            //     ->leftJoin('product_warehouse', function ($join) {
            //         $join->on('products.id', '=', 'product_warehouse.product_id')
            //              ->on('product_batches.id', '=', 'product_warehouse.product_batch_id')
            //              ->on('product_variants.id', '=', 'product_warehouse.variant_id');
            //     })
            //     ->select(
            //         'products.id',
            //         'product_batches.id as product_batch_id',
            //         'product_batches.qty as product_batch_qty',
            //         'products.name',
            //         'products.is_batch',
            //     )
            //     ->where([
            //         ['product_variants.item_code', $product_code[0]],
            //         // ['product_variants.item_code', $product_code[$key]],
            //         ['products.is_active', true]
            //     ])
            //     ->first();
                // dd($lims_batch_data);

            if($action[$key] == '-') {
                $lims_product_data->qty -= $qty[$key];
                // $lims_product_warehouse_data->qty -= $qty[$key];
                $lims_batch_data->product_batch_qty -= $qty[$key];
            }
            elseif($action[$key] == '+') {
                $lims_product_data->qty += $qty[$key];
                // $lims_product_warehouse_data->qty += $qty[$key];
                $lims_batch_data->product_batch_qty += $qty[$key];
            }
            $lims_product_data->save();
            $lims_product_warehouse_data->save();
            // ProductBatch::where('id', $lims_batch_data->product_batch_id)
            //             ->update(['qty' => $lims_batch_data->product_batch_qty]);

            if ($product_batch_id1) {
                $lims_batch_data = ProductBatch::where('id', $product_batch_id1)->first();
                $lims_warehouse_data = Product_Warehouse::where('product_batch_id', $product_batch_id1)->first();

                if ($lims_batch_data) {
                    if ($action[$key] == '-') {
                        $lims_batch_data->qty -= $qty[$key];
                        $lims_warehouse_data->qty -= $qty[$key];
                    } elseif ($action[$key] == '+') {
                        $lims_batch_data->qty += $qty[$key];
                        $lims_warehouse_data->qty += $qty[$key];
                    }

                    $lims_batch_data->save();
                    $lims_warehouse_data->save();
                }
            }

            $product_adjustment['product_id'] = $pro_id;
            $product_adjustment['variant_id'] = $variant_id;
            $product_adjustment['adjustment_id'] = $lims_adjustment_data->id;
            $product_adjustment['qty'] = $qty[$key];
            $product_adjustment['unit_cost'] = $unit_cost[$key];
            $product_adjustment['action'] = $action[$key];
            ProductAdjustment::create($product_adjustment);
        }
        return redirect('qty_adjustment')->with('message', 'Data inserted successfully');
    }
    public function addSaleStore(Request $request)
    {
        // dd($request->all());
        $data = $request->except('document');
        //return $data;
        if( isset($data['stock_count_id']) ){
            $lims_stock_count_data = StockCount::find($data['stock_count_id']);
            $lims_stock_count_data->is_adjusted = true;
            $lims_stock_count_data->save();
        }
        $data['reference_no'] = 'adr-' . date("Ymd") . '-'. date("his");
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move(public_path('documents/adjustment'), $documentName);
            $data['document'] = $documentName;
        }
        $lims_adjustment_data = Adjustment::create($data);

        $product_id = $data['product_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $unit_cost = $data['unit_cost'];
        $action = $data['action'];

        foreach ($product_id as $key => $pro_id) {
            $lims_product_data = Product::find($pro_id);
            if($lims_product_data->is_variant) {
                $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $pro_id],
                    ['variant_id', $lims_product_variant_data->variant_id ],
                    ['warehouse_id', $data['warehouse_id'] ],
                ])->first();

                if($action[$key] == '-'){
                    $lims_product_variant_data->qty -= $qty[$key];
                }
                elseif($action[$key] == '+'){
                    $lims_product_variant_data->qty += $qty[$key];
                }
                $lims_product_variant_data->save();
                $variant_id = $lims_product_variant_data->variant_id;
            }
            else {
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $pro_id],
                    ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                $variant_id = null;
            }

            if($action[$key] == '-') {
                $lims_product_data->qty -= $qty[$key];
                $lims_product_warehouse_data->qty -= $qty[$key];
            }
            elseif($action[$key] == '+') {
                $lims_product_data->qty += $qty[$key];
                $lims_product_warehouse_data->qty += $qty[$key];
            }
            $lims_product_data->save();
            $lims_product_warehouse_data->save();

            $product_adjustment['product_id'] = $pro_id;
            $product_adjustment['variant_id'] = $variant_id;
            $product_adjustment['adjustment_id'] = $lims_adjustment_data->id;
            $product_adjustment['qty'] = $qty[$key];
            $product_adjustment['unit_cost'] = $unit_cost[$key];
            $product_adjustment['action'] = $action[$key];
            ProductAdjustment::create($product_adjustment);
        }
        return redirect('qty_adjustment')->with('message', 'Data inserted successfully');
    }

    public function edit($id)
    {
        $lims_adjustment_data = Adjustment::find($id);
        $lims_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        return view('backend.adjustment.edit', compact('lims_adjustment_data', 'lims_warehouse_list', 'lims_product_adjustment_data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        $lims_adjustment_data = Adjustment::find($id);

        $document = $request->document;
        if ($document) {
            $this->fileDelete(public_path('documents/adjustment/'), $lims_adjustment_data->document);

            $documentName = $document->getClientOriginalName();
            $document->move(public_path('documents/adjustment'), $documentName);
            $data['document'] = $documentName;
        }

        $lims_adjustment_data = Adjustment::find($id);
        $lims_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
        $product_id = $data['product_id'];
        $product_variant_id = $data['product_variant_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $unit_cost = $data['unit_cost'];
        $action = $data['action'];
        $old_product_variant_id = [];
        foreach ($lims_product_adjustment_data as $key => $product_adjustment_data) {
            $old_product_id[] = $product_adjustment_data->product_id;
            $lims_product_data = Product::find($product_adjustment_data->product_id);
            if($product_adjustment_data->variant_id) {
                $lims_product_variant_data = ProductVariant::where([
                    ['product_id', $product_adjustment_data->product_id],
                    ['variant_id', $product_adjustment_data->variant_id]
                ])->first();
                $old_product_variant_id[$key] = $lims_product_variant_data->id;

                if($product_adjustment_data->action == '-') {
                    $lims_product_variant_data->qty += $product_adjustment_data->qty;
                }
                elseif($product_adjustment_data->action == '+') {
                    $lims_product_variant_data->qty -= $product_adjustment_data->qty;
                }
                $lims_product_variant_data->save();
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $product_adjustment_data->product_id],
                    ['variant_id', $product_adjustment_data->variant_id],
                    ['warehouse_id', $lims_adjustment_data->warehouse_id]
                ])->first();
            }
            else {
                $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
            }
            if($product_adjustment_data->action == '-'){
                $lims_product_data->qty += $product_adjustment_data->qty;
                $lims_product_warehouse_data->qty += $product_adjustment_data->qty;
            }
            elseif($product_adjustment_data->action == '+'){
                $lims_product_data->qty -= $product_adjustment_data->qty;
                $lims_product_warehouse_data->qty -= $product_adjustment_data->qty;
            }
            $lims_product_data->save();
            $lims_product_warehouse_data->save();

            if($product_adjustment_data->variant_id && !(in_array($old_product_variant_id[$key], $product_variant_id)) ){
                $product_adjustment_data->delete();
            }
            elseif( !(in_array($old_product_id[$key], $product_id)) )
                $product_adjustment_data->delete();
        }

        foreach ($product_id as $key => $pro_id) {
            $lims_product_data = Product::find($pro_id);
            if($lims_product_data->is_variant) {
                $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $pro_id],
                    ['variant_id', $lims_product_variant_data->variant_id ],
                    ['warehouse_id', $data['warehouse_id'] ],
                ])->first();
                //return $action[$key];

                if($action[$key] == '-'){
                    $lims_product_variant_data->qty -= $qty[$key];
                }
                elseif($action[$key] == '+'){
                    $lims_product_variant_data->qty += $qty[$key];
                }
                $lims_product_variant_data->save();
                $variant_id = $lims_product_variant_data->variant_id;
            }
            else {
                $lims_product_warehouse_data = Product_Warehouse::where([
                    ['product_id', $pro_id],
                    ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                $variant_id = null;
            }

            if($action[$key] == '-'){
                $lims_product_data->qty -= $qty[$key];
                $lims_product_warehouse_data->qty -= $qty[$key];
            }
            elseif($action[$key] == '+'){
                $lims_product_data->qty += $qty[$key];
                $lims_product_warehouse_data->qty += $qty[$key];
            }
            $lims_product_data->save();
            $lims_product_warehouse_data->save();

            $product_adjustment['product_id'] = $pro_id;
            $product_adjustment['variant_id'] = $variant_id;
            $product_adjustment['adjustment_id'] = $id;
            $product_adjustment['qty'] = $qty[$key];
            $product_adjustment['unit_cost'] = $unit_cost[$key];
            $product_adjustment['action'] = $action[$key];

            if($product_adjustment['variant_id'] && in_array($product_variant_id[$key], $old_product_variant_id)) {
                ProductAdjustment::where([
                    ['product_id', $pro_id],
                    ['variant_id', $product_adjustment['variant_id']],
                    ['adjustment_id', $id]
                ])->update($product_adjustment);
            }
            elseif( $product_adjustment['variant_id'] === null && in_array($pro_id, $old_product_id) ){
                ProductAdjustment::where([
                ['adjustment_id', $id],
                ['product_id', $pro_id]
                ])->update($product_adjustment);
            }
            else
                ProductAdjustment::create($product_adjustment);
        }
        $lims_adjustment_data->update($data);
        return redirect('qty_adjustment')->with('message', 'Data updated successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $adjustment_id = $request['adjustmentIdArray'];
        foreach ($adjustment_id as $id) {
            $lims_adjustment_data = Adjustment::find($id);
            $this->fileDelete(public_path('documents/adjustment/'), $lims_adjustment_data->document);

            $lims_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
            foreach ($lims_product_adjustment_data as $key => $product_adjustment_data) {
                $lims_product_data = Product::find($product_adjustment_data->product_id);
                if($product_adjustment_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($product_adjustment_data->product_id, $product_adjustment_data->variant_id)->first();
                    $lims_product_warehouse_data = Product_Warehouse::where([
                            ['product_id', $product_adjustment_data->product_id],
                            ['variant_id', $product_adjustment_data->variant_id],
                            ['warehouse_id', $lims_adjustment_data->warehouse_id]
                        ])->first();
                    if($product_adjustment_data->action == '-'){
                        $lims_product_variant_data->qty += $product_adjustment_data->qty;
                    }
                    elseif($product_adjustment_data->action == '+'){
                        $lims_product_variant_data->qty -= $product_adjustment_data->qty;
                    }
                    $lims_product_variant_data->save();
                }
                else {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                            ['product_id', $product_adjustment_data->product_id],
                            ['warehouse_id', $lims_adjustment_data->warehouse_id]
                        ])->first();
                }
                if($product_adjustment_data->action == '-'){
                    $lims_product_data->qty += $product_adjustment_data->qty;
                    $lims_product_warehouse_data->qty += $product_adjustment_data->qty;
                }
                elseif($product_adjustment_data->action == '+'){
                    $lims_product_data->qty -= $product_adjustment_data->qty;
                    $lims_product_warehouse_data->qty -= $product_adjustment_data->qty;
                }
                $lims_product_data->save();
                $lims_product_warehouse_data->save();
                $product_adjustment_data->delete();
            }
            $lims_adjustment_data->delete();
        }
        return 'Data deleted successfully';
    }

    public function destroy($id)
    {
        $lims_adjustment_data = Adjustment::find($id);
        $lims_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
        foreach ($lims_product_adjustment_data as $key => $product_adjustment_data) {
            $lims_product_data = Product::find($product_adjustment_data->product_id);
            if($product_adjustment_data->variant_id) {
                $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($product_adjustment_data->product_id, $product_adjustment_data->variant_id)->first();
                $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['variant_id', $product_adjustment_data->variant_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
                if($product_adjustment_data->action == '-'){
                    $lims_product_variant_data->qty += $product_adjustment_data->qty;
                }
                elseif($product_adjustment_data->action == '+'){
                    $lims_product_variant_data->qty -= $product_adjustment_data->qty;
                }
                $lims_product_variant_data->save();
            }
            else {
                $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
            }
            if($product_adjustment_data->action == '-'){
                $lims_product_data->qty += $product_adjustment_data->qty;
                $lims_product_warehouse_data->qty += $product_adjustment_data->qty;
            }
            elseif($product_adjustment_data->action == '+'){
                $lims_product_data->qty -= $product_adjustment_data->qty;
                $lims_product_warehouse_data->qty -= $product_adjustment_data->qty;
            }
            $lims_product_data->save();
            $lims_product_warehouse_data->save();
            $product_adjustment_data->delete();
        }
        $lims_adjustment_data->delete();
        $this->fileDelete(public_path('documents/adjustment/'), $lims_adjustment_data->document);

        return redirect('qty_adjustment')->with('not_permitted', 'Data deleted successfully');
    }
}
