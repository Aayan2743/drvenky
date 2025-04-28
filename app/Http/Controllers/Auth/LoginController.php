<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Enums\Status;
use Illuminate\Http\Request;
use App\Libraries\AppLibrary;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use App\Services\PermissionService;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Services\DefaultAccessService;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public string $token;
    public DefaultAccessService $defaultAccessService;
    public PermissionService $permissionService;
    public MenuService $menuService;

    public function __construct(
        MenuService          $menuService,
        PermissionService    $permissionService,
        DefaultAccessService $defaultAccessService
    ) {
        $this->menuService          = $menuService;
        $this->permissionService    = $permissionService;
        $this->defaultAccessService = $defaultAccessService;
    }

    /**
     * @throws \Exception
     */


     public function sendOTP(Request $request): JsonResponse
    {
       
      
        $validated = $request->validate([
            'phone' => 'required|numeric|digits:10|exists:users,phone',  // Assuming phone number is a 10-digit number
        ]);

        $phone = $validated['phone'];

        // MSG91 API URL
        $url = "https://api.msg91.com/api/v5/otp";

        // Send OTP
        $response = Http::withHeaders([
            'authkey' => env('MSG91_AUTH_KEY'),
        ])->post($url, [
            'template_id' => '679231c6d6fc054113134b92',  // Optional, if you're using a predefined template
            'mobile' => "91".$phone,
            // 'otp' => rand(1000, 9999), // Generate a random OTP or handle it dynamically
        ]);

        // Save OTP and mobile number in the session or database to verify later
        // session(['otp' => $response['otp'], 'phone' => $phone]);

        return response()->json(['message' => 'OTP sent successfully']);
    }
    
 
     public function verifyOTP(Request $request) :JsonResponse
     {
        
        $otp = $request->input('otp');
        $phone = "+91".$request->input('phone');
           

        //dd( $phone);
     
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://control.msg91.com/api/v5/otp/verify?otp=$otp&mobile=$phone",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "authkey:" . env('MSG91_AUTH_KEY')  // Get the auth key from your .env file
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);
        $err = curl_error($curl);  // Check for cURL errors

        curl_close($curl);  // Close the cURL session

        if ($err) {
            // Handle cURL errors (if any)
            return response()->json(['message' => 'cURL Error #: ' . $err], 500);
        } else {
            // Decode the response
            $responseData = json_decode($response, true);
            // dd($responseData);
            // Check if the OTP verification was successful
            if ($responseData['type'] == 'success') {
                // OTP verified successfully
                

                // if (!Auth::guard('web')->attempt($request->only('phone'))) {
                //     return new JsonResponse([
                //         'errors' => ['validation' => trans('all.message.credentials_invalid')]
                //     ], 400);
                // }
                    $user = User::where(['phone' => $request->input('phone')])->first();
                    $this->token = $user->createToken('auth_token')->plainTextToken;
        
                    if (!isset($user->roles[0])) {
                        return new JsonResponse([
                            'errors' => ['validation' => trans('all.message.role_exist')]
                        ], 400);
                    }
                
                    $permission        = PermissionResource::collection($this->permissionService->permission($user->roles[0]));
                    $defaultPermission = AppLibrary::defaultPermission($permission);
                
                    return new JsonResponse([
                        'message'           => trans('all.message.login_success'),
                        'token'             => $this->token,
                        'user'              => new UserResource($user),
                        'menu'              => MenuResource::collection(collect($this->menuService->menu($user->roles[0]))),
                        'permission'        => $permission,
                        'user_type'         => (object) ['type' => $user->user_type],
                        'defaultPermission' => $defaultPermission,
                    ], 201);
                
                
                
                // return response()->json([



                //     'message' => 'OTP verified successfully',
                //     'status' => true,
                //     'user'=>$user
                // ]);
            } else {
                // OTP verification failed
                // return response()->json([
                //     'message' => 'Invalid OTP or OTP expired',
                //     'status' => false
                // ], 400);

                return response()->json([
                    'message' => 'Invalid OTP or OTP expired',
                    'status' => false
                ], 400);
                
            }
        }





     }



     public function login223(Request $request): JsonResponse
     {
        dd(formatPrice(12345.67));
         $isOtpLogin = $request->has('otp'); // Check if 'otp' is provided in the request for OTP login.
     
         $validator = Validator::make(
             $request->all(),
             [
                 'email'        => $request['phone'] ? ['nullable', 'string', 'email', 'max:255'] : ['required', 'string', 'email', 'max:255'],
                 'phone'        => $isOtpLogin ? ['required', 'string', 'max:20'] : ($request['email'] ? ['nullable', 'string', 'max:20'] : ['required', 'string', 'max:20']),
                 'country_code' => $isOtpLogin ? ['required', 'string', 'max:20'] : ($request['email'] ? ['nullable', 'string', 'max:20'] : ['required', 'string', 'max:20']),
                 'otp'          => $isOtpLogin ? ['required', 'string', 'max:6'] : ['nullable'],
                 'password'     => $isOtpLogin ? ['nullable'] : ['required', 'string', 'min:6'],
             ],
         );
     
         if ($validator->fails()) {
             if (!$request['email'] && !$request['phone']) {
                 return new JsonResponse([
                     'errors' => [
                         'email_or_phone' => trans('all.message.email_or_phone_required'),
                     ] + $validator->errors()->toArray()
                 ], 422);
             } else {
                 return new JsonResponse([
                     'errors' => $validator->errors()
                 ], 422);
             }
         }
     
         $request->merge(['status' => Status::ACTIVE]);
     
         // OTP Login Logic
         if ($isOtpLogin) {
             $user = User::where([
                 'phone'        => $request['phone'],
                 'country_code' => $request['country_code'],
                 'status'       => Status::ACTIVE,
             ])->first();
     
             if (!$user) {
                 return new JsonResponse([
                     'errors' => ['validation' => trans('all.message.user_not_found')]
                 ], 404);
             }
     
             // Verify OTP with MSG91
             $otpVerification = $this->verifyOtp($request['phone'], $request['country_code'], $request['otp']);
             if (!$otpVerification['status']) {
                 return new JsonResponse([
                     'errors' => ['otp' => trans('all.message.otp_invalid')]
                 ], 400);
             }
         } else {
             // Regular Login Logic
             if ($request['email']) {
                 if (!Auth::guard('web')->attempt($request->only('email', 'password', 'status'))) {
                     return new JsonResponse([
                         'errors' => ['validation' => trans('all.message.credentials_invalid')]
                     ], 400);
                 }
                 $user = User::where('email', $request['email'])->first();
             } else {
                 if (!Auth::guard('web')->attempt($request->only('country_code', 'phone', 'password', 'status'))) {
                     return new JsonResponse([
                         'errors' => ['validation' => trans('all.message.credentials_invalid')]
                     ], 400);
                 }
                 $user = User::where(['phone' => $request['phone'], 'country_code' => $request->country_code])->first();
             }
         }
     
         $this->token = $user->createToken('auth_token')->plainTextToken;
     
         if (!isset($user->roles[0])) {
             return new JsonResponse([
                 'errors' => ['validation' => trans('all.message.role_exist')]
             ], 400);
         }
     
         $permission        = PermissionResource::collection($this->permissionService->permission($user->roles[0]));
         $defaultPermission = AppLibrary::defaultPermission($permission);
     
         return new JsonResponse([
             'message'           => trans('all.message.login_success'),
             'token'             => $this->token,
             'user'              => new UserResource($user),
             'menu'              => MenuResource::collection(collect($this->menuService->menu($user->roles[0]))),
             'permission'        => $permission,
             'user_type'         => (object) ['type' => $user->user_type],
             'defaultPermission' => $defaultPermission,
         ], 201);
     }
     
     /**
      * Verify OTP using MSG91 API
      */
    //  private function verifyOtp(string $phone, string $countryCode, string $otp): array
    //  {
    //      $apiKey = env('MSG91_AUTH_KEY');
    //      $endpoint = "https://api.msg91.com/api/v5/otp/verify";
     
    //      $response = Http::post($endpoint, [
    //          'authkey' => $apiKey,
    //          'mobile'  => $countryCode . $phone,
    //          'otp'     => $otp,
    //      ]);
     
    //      return $response->json();
    //  }
     


    public function login(Request $request): JsonResponse
    {

     
        $validator = Validator::make(
            $request->all(),
            [
                'email'        => $request['phone'] ? ['nullable', 'string', 'email', 'max:255'] : ['required', 'string', 'email', 'max:255'],
                'phone'        => $request['email'] ? ['nullable', 'string', 'max:20'] : ['required', 'string', 'max:20'],
                'country_code' => $request['email'] ? ['nullable', 'string', 'max:20'] : ['required', 'string', 'max:20'],
                'password'     => ['required', 'string', 'min:6'],
            ],
        );

        if ($validator->fails()) {
            if (!$request['email'] && !$request['phone']) {
                return new JsonResponse([
                    'errors' => [
                        'email_or_phone' => trans('all.message.email_or_phone_required'),
                    ] + $validator->errors()->toArray()
                ], 422);
            } else {
                return new JsonResponse([
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        $request->merge(['status' => Status::ACTIVE]);

        if ($request['email']) {
            if (!Auth::guard('web')->attempt($request->only('email', 'password', 'status'))) {
                return new JsonResponse([
                    'errors' => ['validation' => trans('all.message.credentials_invalid')]
                ], 400);
            }
            $user = User::where('email', $request['email'])->first();
        } else {
            if (!Auth::guard('web')->attempt($request->only('country_code', 'phone', 'password', 'status'))) {
                return new JsonResponse([
                    'errors' => ['validation' => trans('all.message.credentials_invalid')]
                ], 400);
            }
            $user = User::where(['phone' => $request['phone'], 'country_code' => $request->country_code])->first();
        }

        $this->token = $user->createToken('auth_token')->plainTextToken;

        if (!isset($user->roles[0])) {
            return new JsonResponse([
                'errors' => ['validation' => trans('all.message.role_exist')]
            ], 400);
        }
      
        $permission        = PermissionResource::collection($this->permissionService->permission($user->roles[0]));
        $defaultPermission = AppLibrary::defaultPermission($permission);

        return new JsonResponse([
            'message'           => trans('all.message.login_success'),
            'token'             => $this->token,
            'user'              => new UserResource($user),
            'menu'              => MenuResource::collection(collect($this->menuService->menu($user->roles[0]))),
            'permission'        => $permission,
            
            'user_type' => (object) ['type' => $user->user_type],
            'defaultPermission' => $defaultPermission,
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
       
     
        $request->user()->currentAccessToken()->delete();

      
        return new JsonResponse([
            'message' => trans('all.message.logout_success')
        ], 200);
    }
}
