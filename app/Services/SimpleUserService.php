<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;


class SimpleUserService
{
    public $userFilter = ['name', 'email', 'balance', 'phone'];
    public $roleFilter = ['role_id'];


    public function list(PaginateRequest $request)
    {

      
        try {
            $requests    = $request->all();
            
            
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

           
// old code working

$sss= User::where('user_type', 5)
->where(function ($query) use ($requests) {
    foreach ($requests as $key => $request) {
        if (!empty($request)) {
            $query->where($key, 'like', '%' . $request . '%');
        }
    }
})
->orderBy($orderColumn, $orderType)
->$method($methodValue);




        

            // dd($results);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function list_operators(PaginateRequest $request)
    {

        //dd("dsfjhdsf");
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            // return User::select('id', 'name', 'email', 'phone') // specify fields here
            //     ->where(function ($query) use ($requests) {
            //         foreach ($requests as $key => $request) {
            //             if (in_array($key, $this->roleFilter)) {
            //                 $query->whereHas('roles', function ($query) use ($request) {
            //                     $query->where('id', '=', $request);
            //                 });
            //             }
            //             if (in_array($key, $this->userFilter)) {
            //                 $query->where($key, 'like', '%' . $request . '%');
            //             }
            //         }
            //     })->orderBy($orderColumn, $orderType)->$method(
            //         $methodValue
            //     );



            return User::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->roleFilter)) {
                        $query->whereHas('roles', function ($query) use ($request) {
                            // Filter roles with id 4
                            $query->where('id', '=', 4); 
                        });
                    }
                    if (in_array($key, $this->userFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );

            // dd($results);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function list_customers_dummy(PaginateRequest $request)
    {

        //dd("dsfjhdsf");
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            // return User::select('id', 'name', 'email', 'phone') // specify fields here
            //     ->where(function ($query) use ($requests) {
            //         foreach ($requests as $key => $request) {
            //             if (in_array($key, $this->roleFilter)) {
            //                 $query->whereHas('roles', function ($query) use ($request) {
            //                     $query->where('id', '=', $request);
            //                 });
            //             }
            //             if (in_array($key, $this->userFilter)) {
            //                 $query->where($key, 'like', '%' . $request . '%');
            //             }
            //         }
            //     })->orderBy($orderColumn, $orderType)->$method(
            //         $methodValue
            //     );



            return User::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->roleFilter)) {
                        $query->whereHas('roles', function ($query) use ($request) {
                            // Filter roles with id 1  for customers
                            $query->where('id', '=', 1); 
                        });
                    }
                    if (in_array($key, $this->userFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );

            // dd($results);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    public function list_customers(PaginateRequest $request)
    {

       // dd("dsfjhdsf");
        try {
            $requests    = $request->all();
            
            
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            // return User::select('id', 'name', 'email', 'phone') // specify fields here
            //     ->where(function ($query) use ($requests) {
            //         foreach ($requests as $key => $request) {
            //             if (in_array($key, $this->roleFilter)) {
            //                 $query->whereHas('roles', function ($query) use ($request) {
            //                     $query->where('id', '=', $request);
            //                 });
            //             }
            //             if (in_array($key, $this->userFilter)) {
            //                 $query->where($key, 'like', '%' . $request . '%');
            //             }
            //         }
            //     })->orderBy($orderColumn, $orderType)->$method(
            //         $methodValue
            //     );


// old code working

return User::where('user_type', 1)
->where(function ($query) use ($requests) {
    foreach ($requests as $key => $request) {
        // You can process or add additional filters here if needed
        // Example: $query->where($key, $request);
        //dd($request); // Debugging the request data
    }
})
->orderBy($orderColumn, $orderType)
->$method($methodValue);





            // $dddd= User::where(function ($query) use ($requests) {
            //     //  $results= User::where(function ($query) use ($requests) {
            //     // dd($requests);
            //     foreach ($requests as $key => $request) {
                 
            //         if (in_array($key, $this->roleFilter)) {
            //             $query->whereHas('roles', function ($query) use ($request) {

                         
            //                 $query->where('id', '=',$request);
            //             });
            //         }
            //         if (in_array($key, $this->userFilter)) {
            //             $query->where($key, 'like', '%' . $request . '%');
            //         }
            //     }
            // })->orderBy($orderColumn, $orderType)->$method(
            //     $methodValue
            // );

            //dd($get_data);

          

            // return User::where(function ($query) use ($requests) {
            // $dddd= User::where(function ($query) use ($requests) {
            //     foreach ($requests as $key => $request) {
            //         if (in_array($key, $this->roleFilter)) {
            //             $query->whereHas('roles', function ($query) {
            //                 $query->where('name', '=', 'POS Operator'); // Adjust 'name' to your column for the role identifier
            //             });
            //         }
            //         if (in_array($key, $this->userFilter)) {
            //             $query->where($key, 'like', '%' . $request . '%');
            //         }
            //     }
            // })
            // ->orderBy($orderColumn ?? 'id', $orderType ?? 'asc')
            // ->$method($methodValue ?? null);


            // dd($dddd);


            // dd($results);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
