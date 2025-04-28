<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Traits\ApiRequestTrait;
use App\Services\ProductService;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ImportFileRequest;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\ProductOfferRequest;
use App\Http\Resources\ProductAdminResource;
use App\Http\Requests\ShippingAndReturnRequest;
use App\Http\Resources\ProductDetailsAdminResource;
use App\Http\Resources\SimpleProductDetailsResource; 
use App\Http\Resources\simpleProductWithVariationCountResource;
use Str;
use Validator;
use App\Enums\EnumRole;
use Illuminate\Support\Facades\DB;
use App\Mail\QueryFormMail;
use Illuminate\Support\Facades\Mail;


use Illuminate\Support\Facades\Log;
class ProductController extends AdminController
{
    use ApiRequestTrait;
    public ProductService $productService;
    protected $apiRequest;

    public function add_customer_old(Request $req){
        
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone',
            // 'email' => 'required|email|unique:users,email',
            // 'password' => 'required|string|min:6', // Ensure password requirements
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }




        $add_customer=User::create([
            'name'=>$req->name,
            'phone'=>$req->phone,
            'email'=>Str::slug($req->name) . rand(1, 500).'@yahoo.com',
            'password'=>$req->name,
            'username' => Str::slug($req->name) . rand(1, 500),
            'country_code'=>'+91',
            'user_type' => 1,
           
        ]);

        $add_customer->assignRole(2);
        $response = [
            "id" => $add_customer->id,
            "name" => $add_customer->name,
            "email" => $add_customer->email,
            "phone" => $add_customer->phone,
        ];
    
        // Return the response in JSON format
      

        if($add_customer){

            return response()->json($response, 200);
            // return response()->json([
            //     'status'=>true,
            //     'data'=>$add_customer->id,

    // return response()->json(['id' => $add_customer->id], 200);
            // ]);
        }else{
            return response()->json([
                'status'=>false
            ]);
        }


    }

    public function add_customer(Request $req){
        
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone',
            // 'email' => 'required|email|unique:users,email',
            // 'password' => 'required|string|min:6', // Ensure password requirements
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }



        $add_customer=User::create([
            'name'=>$req->name,
            'phone'=>$req->phone,
            'email'=>Str::slug($req->name) . rand(1, 500).'@yahoo.com',
            'password'=>$req->name,
            'username' => Str::slug($req->name) . rand(1, 500),
            'country_code'=>'+91',
            'user_type' => 1,
           
        ]);

        $add_customer->assignRole(2);
        $response = [
            "id" => $add_customer->id,
            "name" => $add_customer->name,
            "email" => $add_customer->email,
            "phone" => $add_customer->phone,
        ];
    
        // Return the response in JSON format
      

        if($add_customer){

            return response()->json($response, 200);
            // return response()->json([
            //     'status'=>true,
            //     'data'=>$add_customer->id,

    // return response()->json(['id' => $add_customer->id], 200);
            // ]);
        }else{
            return response()->json([
                'status'=>false
            ]);
        }

    }

    public function query_form(Request $req){
        //  dd($req->all());
  
        $validated = $req->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|email|max:255',
          'phone' => 'required|digits:10',
          'message' => 'required|string',
       ]);
  
          //Mail::to('sk.asif0490@gmail.com')->send(new QueryFormMail($validated));
          Mail::to(env('QUERY_FORM_MAIL'))->send(new QueryFormMail($validated));
      return response()->json([
          'success' => true,
          'message' => 'Your query has been sent successfully.',
      ]);
  
      }



    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
         $this->apiRequest     = $this->makeApiRequest();
        $this->middleware(['permission:products'])->only('export', 'generateSku', 'downloadAttachment');
        $this->middleware(['permission:products_create'])->only('store', 'uploadImage', 'import');
        $this->middleware(['permission:products_edit'])->only('update');
        $this->middleware(['permission:products_delete'])->only('destroy', 'deleteImage');
        $this->middleware(['permission:products_show'])->only('show', 'shippingAndReturn');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ProductAdminResource::collection($this->productService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function search_byid($id){

        try{

          
         
            
            $id = trim(strval($id));
            DB::enableQueryLog();
            // $get_product=Product::where('barcode_id','=',$id)->get();

            // $get_product = Product::where('barcode_id', '=', $id)->get();

            // $get_product = Product::withTrashed()->where('barcode_id', '=', $id)->get();

            $get_product = Product::with(['media','variations','taxes'])->withTrashed()->where('barcode_id', '=', $id)->get();


            // Log::info('Executed Query', DB::getQueryLog());
            // Log::info('Product fetch request', [
            //     'get_data' => $get_product->toArray(), // Convert to array for logging
            //     'barcode_id' => $id,
            //     'result_count' => $get_product->count(),
            //     'user_id' => auth()->id()
            // ]);

            return response()->json(['status'=>true,'product'=>$get_product]);

        }catch(Exception $exception){
            Log::info($exception->getMessage());
        }
           
       

    }


    

    public function show(Product $product): \Illuminate\Foundation\Application|\Illuminate\Http\Response|ProductDetailsAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
       // dd("fgjdfg");
        try {

            // return $this->productService->show($product);
            // dd($this->productService->show($product));
            return new ProductDetailsAdminResource($this->productService->show($product));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(ProductRequest $request): \Illuminate\Http\Response|ProductAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {


        // dd( $request->all());
        try {

            //dd($this->apiRequest->status);
          
            //return new ProductAdminResource($this->productService->store($request));
            //return response(['status' => true, 'message' => 'Added'], 200);

            if (env('DEMO')) {
              
             
                return new ProductAdminResource($this->productService->store($request));
            } else {
             // if ($this->apiRequest->status) {
                    return new ProductAdminResource($this->productService->store($request));
              //  }
                return response(['status' => false, 'message' => $this->apiRequest->message], 422);
            }
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(ProductRequest $request, Product $product): \Illuminate\Http\Response|ProductAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            
            return new ProductAdminResource($this->productService->update($request, $product));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    // public function update(ProductRequest $request, Product $product): \Illuminate\Http\Response|ProductAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
//     public function update(Request $request, Product $product): \Illuminate\Http\Response|ProductAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
//     {
//        // dd("fdjghfg here");

//        // dd($product);
//        // dd("update call");
//    //     dd($requests->all());
//         try {
//             if (!$this->apiRequest->status) {
// //return new ProductAdminResource($this->productService->store($request));
//                 return new ProductAdminResource($this->productService->store($request, $product));
//             }

          
//         } catch (Exception $exception) {
//             return response(['status' => false, 'message' => $exception->getMessage()], 422);
//         }
//     }

    public function destroy(Product $product): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->productService->destroy($product);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function uploadImage(ChangeImageRequest $request, Product $product): \Illuminate\Foundation\Application|\Illuminate\Http\Response|ProductDetailsAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductDetailsAdminResource($this->productService->uploadImage($request, $product));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function deleteImage(Product $product, $index): \Illuminate\Foundation\Application|\Illuminate\Http\Response|ProductDetailsAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductDetailsAdminResource($this->productService->deleteImage($product, $index));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new ProductExport($this->productService, $request), 'Product.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function generateSku(): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return response(['data' => ['product_sku' => $this->productService->generateSku()]], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function shippingAndReturn(ShippingAndReturnRequest $request, Product $product): \Illuminate\Foundation\Application|\Illuminate\Http\Response|ProductAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductAdminResource($this->productService->shippingAndReturn($request, $product));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function productOffer(ProductOfferRequest $request, Product $product): \Illuminate\Foundation\Application|\Illuminate\Http\Response|ProductAdminResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ProductAdminResource($this->productService->productOffer($request, $product));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function purchasableProducts(): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return  simpleProductWithVariationCountResource::collection($this->productService->purchasableProducts());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function simpleProducts(): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return  simpleProductWithVariationCountResource::collection($this->productService->simpleProducts());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    
    public function posProduct1(Product $product, Request $request): SimpleProductDetailsResource|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new SimpleProductDetailsResource($this->productService->showWithRelation1($product, $request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function posProduct(Product $product, Request $request): SimpleProductDetailsResource|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new SimpleProductDetailsResource($this->productService->showWithRelation($product, $request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function downloadAttachment()
    {
        try {
            return Response::download(public_path('/file/ProductImportSample.xlsx'));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function import(ImportFileRequest $request)
    {
        try {
            Excel::import(new ProductImport($request->file('file')), $request->file('file'));
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}