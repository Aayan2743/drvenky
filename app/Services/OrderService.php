<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\ProductVariation;
use Exception;
use App\Models\User;
use App\Enums\Status;
use App\Models\Order;
use App\Models\Stock;
use App\Enums\OrderType;
use App\Models\StockTax;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Events\SendOrderSms;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PosOrderRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\PaymentStatusRequest;

class OrderService
{
    public object $order;
    protected array $orderFilter = [
        'order_serial_no',
        'user_id',
        'total',
        'order_type',
        'order_datetime',
        'payment_method',
        'payment_status',
        'status',
        'active',
        'source',
        'created_by'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {

     
        try {

            
            $requests    = $request->all();

            

            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';


            //dd($request->user_id);
        //   dd($requests);


        
      
        // return Order::with('transaction', 'orderProducts', 'user_data')
        // ->where(function ($query) use ($requests) {
        //     if (isset($requests['user_id']) && is_numeric($requests['user_id'])) {
        //         $query->where('created_by', (int)$requests['user_id']);
        //     }

        //     if (isset($requests['from_date']) && isset($requests['to_date'])) {
        //         $first_date = Date('Y-m-d', strtotime($requests['from_date']));
        //         $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
        //         $query->whereDate('order_datetime', '>=', $first_date)
        //               ->whereDate('order_datetime', '<=', $last_date);
        //     }

        //     // foreach ($requests as $key => $request) {
        //     //     // Apply filters in orderFilter
        //     //     if (in_array($key, $this->orderFilter)) {
        //     //         if ($key === "status") {
        //     //             $query->where($key, (int)$request);
        //     //         } else {
        //     //             $query->where($key, 'like', '%' . $request . '%');
        //     //         }
        //     //     }
    
        //     //     // Apply exception filters
        //     //     if (in_array($key, $this->exceptFilter)) {
        //     //         $explodes = explode('|', $request);
        //     //         if (is_array($explodes)) {
        //     //             foreach ($explodes as $explode) {
        //     //                 $query->where('order_type', '!=', $explode);
        //     //             }
        //     //         }
        //     //     }
        //     // }


        // })


        // ->get();


        // return Order::with('transaction', 'orderProducts','user_data')->where(function ($query) use ($requests) {
            
           




        //     if (isset($requests['from_date']) && isset($requests['to_date'])) {
        //             $first_date = Date('Y-m-d', strtotime($requests['from_date']));
        //             $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
        //             $query->whereDate('order_datetime', '>=', $first_date)->whereDate(
        //                 'order_datetime',
        //                 '<=',
        //                 $last_date
        //             );
        //         }
                   
        //         foreach ($requests as $key => $request) {
                  
                   
                   
        //             if (in_array($key, haystack: $this->orderFilter)) {

                      
                      
        //                 if ($key === "status") {
        //                     $query->where($key, (int)$request);
        //                 } else {
        //                     $query->where($key, 'like', '%' . $request . '%');
        //                 }






                      

        //             }




        //             if (in_array($key, $this->exceptFilter)) {
                           

        //                 $explodes = explode('|', $request);
        //                 if (is_array($explodes)) {
        //                     foreach ($explodes as $explode) {
        //                         $query->where('order_type', '!=', $explode);
        //                     }
        //                 }
        //             }


                    


                  
        //             // if (isset($requests['user_id'])) {
        //             //     $query->where('created_by', (int)$requests['user_id']);
        //             // }

                   


        //         }
              
               
        //     })->orderBy($orderColumn, $orderType)->$method(
        //         $methodValue
        //     );


        return Order::with('transaction', 'orderProducts', 'user_data')
        ->where(function ($query) use ($requests) {
            // Filter by date range
            if (isset($requests['from_date']) && isset($requests['to_date'])) {
                $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
                $query->whereDate('order_datetime', '>=', $first_date)
                      ->whereDate('order_datetime', '<=', $last_date);
            }

            if (isset($requests['user_id']) && is_numeric($requests['user_id'])) {
              
              
               
                $query->where('created_by', (int)$requests['user_id']);



   
}

        $query->where('order_type', 15);
    
           
           
        })
        ->orderBy($orderColumn, $orderType)
        ->$method($methodValue);
    


        // dd($dddddd);


            // return Order::with('transaction', 'orderProducts', 'user_data')->where(function ($query) use ($requests) {
            //     // Filter by date range if provided
            //     if (isset($requests['from_date']) && isset($requests['to_date'])) {
            //         $first_date = Date('Y-m-d', strtotime($requests['from_date']));
            //         $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
            //         $query->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date);
            //     }
            
            //     // Apply other filters if provided
            //     foreach ($requests as $key => $request) {
            //         if (in_array($key, $this->orderFilter)) {
            //             if ($key === "status") {
            //                 $query->where($key, (int)$request);
            //             } else {
            //                 $query->where($key, 'like', '%' . $request . '%');
            //             }
            //         }
            
            //         if (in_array($key, $this->exceptFilter)) {
            //             $explodes = explode('|', $request);
            //             if (is_array($explodes)) {
            //                 foreach ($explodes as $explode) {
            //                     $query->where('order_type', '!=', $explode);
            //                 }
            //             }
            //         }
            //     }
            
            //     // Add filter for `created_by`
            //     if (isset($requests['created_by'])) {
            //         $query->where('created_by', (int)$requests['created_by']);
            //     }
            
            // })->orderBy($orderColumn, $orderType)->$method(
            //     $methodValue
            // );
            

          
         
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    public function list_online(PaginateRequest $request)
    {
        
     
        try {

            
            $requests    = $request->all();

            

            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';


          

//         return Order::with('transaction', 'orderProducts', 'user_data')
//         ->where(function ($query) use ($requests) {
//             // Filter by date range
//             if (isset($requests['from_date']) && isset($requests['to_date'])) {
//                 $first_date = Date('Y-m-d', strtotime($requests['from_date']));
//                 $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
//                 $query->whereDate('order_datetime', '>=', $first_date)
//                       ->whereDate('order_datetime', '<=', $last_date);
//             }

//             if (isset($requests['user_id']) && is_numeric($requests['user_id'])) {
              
              
               
//                 $query->where('created_by', (int)$requests['user_id']);



   
// }
    
// $query->where('order_type', 5);
        
           
//         })
//         ->orderBy($orderColumn, $orderType)
//         ->$method($methodValue);
    


        // dd($dddddd);


            return Order::with('transaction', 'orderProducts', 'user_data')->where(function ($query) use ($requests) {
                // Filter by date range if provided
                if (isset($requests['from_date']) && isset($requests['to_date'])) {
                    $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                    $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
                    $query->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date);
                }
            
                // Apply other filters if provided
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        if ($key === "status") {
                            $query->where($key, (int)$request);
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
            
                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('order_type', '!=', $explode);
                            }
                        }
                    }
                }
            
                // Add filter for `created_by`
                if (isset($requests['created_by'])) {
                    $query->where('created_by', (int)$requests['created_by']);
                }
            
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
            

          
         
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function myOrder(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::where('order_type', "!=", OrderType::POS)->where(function ($query) use ($requests) {
                $query->where('user_id', auth()->user()->id);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('status', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function userOrder(PaginateRequest $request, User $user)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::where('order_type', "!=", OrderType::POS)->where(function ($query) use ($requests, $user) {
                $query->where('user_id', $user->id);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('status', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function posOrderStore(PosOrderRequest $request): object
    {
        Log::info($request->all());
        
        // dd($request->all());
        try {
            DB::transaction(function () use ($request) {
                $products = json_decode($request->products, true);

// Extract the SKU from the first product (assuming you want to save the first product's SKU)
$sku = isset($products[0]['sku']) ? $products[0]['sku'] : null;

                $this->order = Order::create(
                    $request->validated() + [
                        'user_id'        => $request->customer_id,
                        'status'         => OrderStatus::DELIVERED,
                        'payment_status' => PaymentStatus::PAID,
                        'order_datetime' => date('Y-m-d H:i:s'),
                        'payment_type'=>$request->payment_method,
                        'receivedAmount'=>$request->receivedAmount,
                        'created_by'=>Auth()->user()->id,
                        'sku'            => $sku, 

                    ]
                );

                $products = json_decode($request->products);
                if (!blank($products)) {
                    foreach ($products as $product) {
                    $igst=0;
                    $sgst=0;
                    $cgst=0;

                   
                        $sgst = number_format($product->total_tax / 2, env('CURRENCY_DECIMAL_POINT'), '.', '');
                        $cgst = number_format($product->total_tax / 2, env('CURRENCY_DECIMAL_POINT'), '.', '');
                       


                        $stockId = Stock::create([
                            'product_id'      => $product->product_id,
                            'model_type'      => Order::class,
                            'model_id'        => $this->order->id,
                            'item_type'       => $product->variation_id > 0 ? ProductVariation::class : Product::class,
                            'item_id'         => $product->variation_id > 0 ? $product->variation_id : $product->product_id,
                            'variation_names' => $product->variation_names,
                            'sku'             => $product->sku,
                            'price'           => $product->price,
                            'quantity'        => -$product->quantity,
                            'discount'        => $product->discount,
                            'tax'             => number_format($product->total_tax, env('CURRENCY_DECIMAL_POINT'), '.', ''),
                            'subtotal'        => $product->subtotal,
                            'total'           => $product->total,
                            'status'          => Status::ACTIVE,
                            'order_type'          => $request->order_type,
                               
                                'cgst'          => $cgst,
                                'sgst'          => $sgst,
                                'stateName'          => "Telangana",
                              

                        ]);
                        if ($product->taxes) {
                            $j               = 0;
                            $productTaxArray = [];
                            foreach ($product->taxes as $tax) {
                                $productTaxArray[$j] = [
                                    'stock_id'   => $stockId->id,
                                    'product_id' => $product->product_id,
                                    'tax_id'     => $tax->id,
                                    'name'       => $tax->name,
                                    'code'       => $tax->code,
                                    'tax_rate'   => $tax->tax_rate,
                                    'tax_amount' => $tax->tax_amount,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                $j++;
                            }
                            StockTax::insert($productTaxArray);
                        }
                    }
                }

                $this->order->order_serial_no = date('dmy') . $this->order->id;
                $this->order->save();
            });
            return $this->order;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Order $order, $auth = false): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->load('user');
                    // added code above 
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->load('user');
                // added code above 
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function orderDetails(User $user, Order $order): Order|array
    {
        try {
            if ($order->user_id == $user->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeStatus(Order $order, OrderStatusRequest $request, $auth = false): Order|array
    {

        // 5 confirmed ; 7 on the way ; 10 delivered
     
    // dd($order->shiprocket_json->order_id);

    $shiprocketArray = json_decode(json_encode($order->shiprocket_json), true);

       

    $shiprocketArray = (array) $order->shiprocket_json;
    $shiprocketArray1 = json_decode($shiprocketArray[0], true);// (array) $shiprocketArray[0];

    // dd($shiprocketArray1['order_id']);
 
    
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }

                    if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {
                        if ($order->transaction) {
                            app(PaymentService::class)->cashBack(
                                $order,
                                'credit',
                                rand(111111111111111, 99999999999999)
                            );
                        }
                    }
                    SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    $order->status = $request->status;
                    $order->save();
                }
            } else {
                if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {
                    $request->validate([
                        'reason' => 'required|max:700',
                    ]);

                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }

                    if ($order->transaction) {
                        app(PaymentService::class)->cashBack(
                            $order,
                            'credit',
                            rand(111111111111111, 99999999999999)
                        );
                    }
                }

                // Define template IDs for each status
                    $templateIds = [
                        5 => env('ORDERED_CONFIRMED'), // Confirmed
                        7 => env('ORDERED_SHIPPING_STATUS'), // On the way
                        10 => env('ORDERED_DELIVERED_STATUS'), // Delivered
                    ];



                $courier="Shiprocket";
                $message = '';
                // dd($shiprocketArray1['shipment_id']);

                switch ($request->status) {

                   
                    case 5: // Confirmed

                        //dd(env('SHIPROCKET_TESTING'));

                        if(env('MANUAL_ORDER')){

                            $token=loginShiprocket();
                            // shiprocket order generate
                                // $token = 'YOUR_ACCESS_TOKEN_HERE'; // Replace with your actual token
                                $url = 'https://apiv2.shiprocket.in/v1/external/courier/assign/awb';
    
                                $data = [
                                    "shipment_id" => $shiprocketArray1['shipment_id'],
                                    "courier_id" => "51"
                                ];
    
                                $ch = curl_init($url);
    
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json',
                                    'Authorization: Bearer ' . $token
                                ]);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
                                $response = curl_exec($ch);
                                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
                                if (curl_errno($ch)) {
    
                                    
                                    echo 'cURL Error: ' . curl_error($ch);
                                } else {


                                   
                                    
                               
                                
                                     $awbStatus = json_decode($response, true);
                                
                                    //  dd($awbStatus);

                                     if (isset($awbStatus['status_code']) && $awbStatus['status_code'] == 350) {
                                        throw new Exception('No amount available in Shiprocket. Please recharge and try again.', 422);
                                    }

                                    if (isset($awbStatus['awb_assign_status']) && $awbStatus['awb_assign_status'] == 1) {
                                       
                                        $curl = curl_init();
                                        // dd($shiprocketArray1['shipment_id']);
                                        $data = [
                                            "template_id" => $templateIds[5],  
                                            "realTimeResponse" => "1", // Optional
                                            "recipients" => [
                                                [
                                                    "mobiles" => "91" . $order->user->phone,
                                                    "var1" => $order->user->name,  // Dynamic Name
                                                    "var2" => $order->id,      // Order Amount
                                                    "var3" => $order->total        // Order Amount
                                                ]
                                            ]
                                        ];
                        
                                        $curl = curl_init();
                                        
                                        curl_setopt_array($curl, [
                                            CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => "",
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 30,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => "POST",
                                            CURLOPT_POSTFIELDS => json_encode($data),
                                            CURLOPT_HTTPHEADER => [
                                                "accept: application/json",
                                                "authkey: " . env('MSG91_AUTH_KEY'),  // Add space after "authkey:"
                                                "content-type: application/json"
                                            ],
                                        ]);
                                        
                                        $response = curl_exec($curl);
                                        $err = curl_error($curl);
                                        
                                        curl_close($curl);
                                        
                                        if ($err) {
                                            Log::error("MSG91 cURL Error: " . $err);
                                            // echo "cURL Error #: " . $err;
                                        } else {
                                            Log::info("MSG91 Response: " . $response);
                                            // echo $response;
                                        }
                                        
                                        SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                                        SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                                        SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                                        $order->status = $request->status;
                                        $order->order_json = $awbStatus;
                                        $order->save();


                                       
                                       
                                       
                                        // return redirect()->back()->with('success', 'Order completed successfully. AWB Code: ' . $awbStatus['response']['data']['awb_code']);
                                    }

                                    if (isset($awbStatus['awb_assign_status']) && $awbStatus['awb_assign_status'] == 0 && isset($awbStatus['response']['data']['awb_assign_error'])) {
                                        $errorMessage = $awbStatus['response']['data']['awb_assign_error'];
                                       
                                        SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                                        SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                                        SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                                        $order->status = $request->status;
                                        // $order->order_json = $awbStatus;
                                        $order->save();
                                       
                                       
                                        // return redirect()->back()->with('error', $errorMessage);
                                    }

                                    // if($awbStatus['status_code']==350){
                                    //     throw new Exception($awbStatus['message'], 422);
                                    //        break;
                                    // }
                                    
                                    
                                  
                                  
                                }
    
                                curl_close($ch);

                        }else{

                            $curl = curl_init();
                            // dd($shiprocketArray1['shipment_id']);
                            $data = [
                                "template_id" => $templateIds[5],  
                                "realTimeResponse" => "1", // Optional
                                "recipients" => [
                                    [
                                        "mobiles" => "91" . $order->user->phone,
                                        "var1" => $order->user->name,  // Dynamic Name
                                        "var2" => $order->id,      // Order Amount
                                        "var3" => $order->total        // Order Amount
                                    ]
                                ]
                            ];
            
                            $curl = curl_init();
                            
                            curl_setopt_array($curl, [
                                CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => json_encode($data),
                                CURLOPT_HTTPHEADER => [
                                    "accept: application/json",
                                    "authkey: " . env('MSG91_AUTH_KEY'),  // Add space after "authkey:"
                                    "content-type: application/json"
                                ],
                            ]);
                            
                            $response = curl_exec($curl);
                            $err = curl_error($curl);
                            
                            curl_close($curl);
                            
                            if ($err) {
                                Log::error("MSG91 cURL Error: " . $err);
                                // echo "cURL Error #: " . $err;
                            } else {
                                Log::info("MSG91 Response: " . $response);
                                // echo $response;
                            }
                            
                         




                            SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                            SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                            SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                            $order->status = $request->status;
                          
                            $order->save();

                        }
                       




                        
                    case 7: // On the way
                        // $message = "Dear Customer, your order #{$order->user->name} has been shipped via #{$courier}. Tracking ID: #{$shiprocketArray1['order_id']}. Thank you for shopping! -Dr Venkys Pet Store";
                        // $templateId = $templateIds[7]; 
                        $courier="Shiprocket";
                        $tracking_id="123456789";
                        $curl = curl_init();

                        $data = [
                            "template_id" => $templateIds[7],  
                            "realTimeResponse" => "1", // Optional
                            "recipients" => [
                                [
                                    "mobiles" => "91" . $order->user->phone,
                                    "var1" => $order->id,  // Dynamic Name
                                    "var2" => $courier,      // Order Amount
                                    "var3" => $tracking_id        // Order Amount
                                ]
                            ]
                        ];
                        
                        $curl = curl_init();
                        
                        curl_setopt_array($curl, [
                            CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => json_encode($data),
                            CURLOPT_HTTPHEADER => [
                                "accept: application/json",
                                "authkey: " . env('MSG91_AUTH_KEY'),  // Add space after "authkey:"
                                "content-type: application/json"
                            ],
                        ]);
                        
                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        
                        curl_close($curl);
                        
                        if ($err) {
                            Log::error("MSG91 cURL Error: " . $err);
                            // echo "cURL Error #: " . $err;
                        } else {
                            Log::info("MSG91 Response: " . $response);
                            // echo $response;
                        }





                        break;
                    case 10: // Delivered
                        // $message = "Dear #{$order->user->name}, your order #{$order->id} has been successfully delivered! We hope you and your pet love it! Thank you for shopping with us. If you need any assistance, feel free to reach out. -Dr Venkys Pet Store";
                        // $templateId = $templateIds[10];


                        $curl = curl_init();

                        $data = [
                            "template_id" => $templateIds[10],  
                            "realTimeResponse" => "1", // Optional
                            "recipients" => [
                                [
                                    "mobiles" => "91" . $order->user->phone,
                                    "var1" => $order->user->name,  // Dynamic Name
                                    "var2" => $order->id,      // Order Amount
                                      
                                ]
                            ]
                        ];
                        
                        $curl = curl_init();
                        
                        curl_setopt_array($curl, [
                            CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => json_encode($data),
                            CURLOPT_HTTPHEADER => [
                                "accept: application/json",
                                "authkey: " . env('MSG91_AUTH_KEY'),  // Add space after "authkey:"
                                "content-type: application/json"
                            ],
                        ]);
                        
                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        
                        curl_close($curl);
                        
                        if ($err) {
                            Log::error("MSG91 cURL Error: " . $err);
                            // echo "cURL Error #: " . $err;
                        } else {
                            Log::info("MSG91 Response: " . $response);
                            // echo $response;
                        }
                        break;
                }
              


               // dd("+91" . $order->user->phone);
                if (!empty($message)) {

                   
                }

              



                // SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                // SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                // SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                // $order->status = $request->status;
                // $order->save();
            }
            return $order;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changePaymentStatus(Order $order, PaymentStatusRequest $request, $auth = false): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->payment_status = $request->payment_status;
                    $order->save();
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->payment_status = $request->payment_status;
                $order->save();
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Order $order): void
    {
        try {
            DB::transaction(function () use ($order) {
                if ($order?->orderProducts) {
                    $stockIds = $order?->orderProducts->pluck('id');
                    if (!blank($stockIds)) {
                        StockTax::whereIn('stock_id', $stockIds)->delete();
                    }
                    $order?->orderProducts()->delete();
                }
                $order->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
